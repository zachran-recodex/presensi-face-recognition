<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Location;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    protected FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    public function index()
    {
        $employees = Employee::with(['locations', 'attendances' => function ($query) {
            $query->whereDate('date', today());
        }])->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $locations = Location::where('is_active', true)->get();
        return view('employees.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:employees',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'locations' => 'required|array|min:1',
            'locations.*' => 'exists:locations,id',
            'primary_location' => 'required|in_array:locations.*',
        ]);

        DB::transaction(function () use ($validated) {
            $employee = Employee::create($validated);

            foreach ($validated['locations'] as $locationId) {
                $employee->locations()->attach($locationId, [
                    'is_primary' => $locationId == $validated['primary_location']
                ]);
            }
        });

        return redirect()->route('employees.index')->with('status', 'Employee created successfully');
    }

    public function show(Employee $employee)
    {
        $employee->load(['locations', 'attendances' => function ($query) {
            $query->with('location')->orderBy('date', 'desc')->limit(10);
        }]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('locations');
        $locations = Location::where('is_active', true)->get();

        return view('employees.edit', compact('employee', 'locations'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', Rule::unique('employees')->ignore($employee->id)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'locations' => 'required|array|min:1',
            'locations.*' => 'exists:locations,id',
            'primary_location' => 'required|in_array:locations.*',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated, $employee) {
            $employee->update($validated);

            $employee->locations()->detach();

            foreach ($validated['locations'] as $locationId) {
                $employee->locations()->attach($locationId, [
                    'is_primary' => $locationId == $validated['primary_location']
                ]);
            }
        });

        return redirect()->route('employees.index')->with('status', 'Employee updated successfully');
    }

    public function destroy(Employee $employee)
    {
        try {
            if ($employee->face_registered) {
                $this->faceService->deleteFace($employee->employee_id);
            }
        } catch (\Exception $e) {
            // Log error but continue with deletion
            \Log::warning('Failed to delete face from API: ' . $e->getMessage());
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('status', 'Employee deleted successfully');
    }

    public function registerFace(Request $request, Employee $employee)
    {
        $request->validate([
            'face_image' => 'required|string', // base64 image
        ]);

        try {
            $response = $this->faceService->enrollFace(
                $employee->employee_id,
                $employee->name,
                $request->face_image
            );

            $employee->update([
                'face_registered' => true,
                'face_gallery_id' => config('services.face_recognition.gallery_id')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Face registered successfully',
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register face: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFace(Employee $employee)
    {
        try {
            $this->faceService->deleteFace($employee->employee_id);

            $employee->update([
                'face_registered' => false,
                'face_gallery_id' => null
            ]);

            return redirect()->back()->with('status', 'Face registration deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete face: ' . $e->getMessage());
        }
    }
}
