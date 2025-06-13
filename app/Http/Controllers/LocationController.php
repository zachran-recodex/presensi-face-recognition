<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('employees')->paginate(15);
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'required|integer|min:50|max:1000',
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')->with('status', 'Location created successfully');
    }

    public function show(Location $location)
    {
        $location->load(['employees', 'attendances' => function ($query) {
            $query->with('employee')->whereDate('date', today());
        }]);

        return view('locations.show', compact('location'));
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'required|integer|min:50|max:1000',
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
            'is_active' => 'boolean',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')->with('status', 'Location updated successfully');
    }

    public function destroy(Location $location)
    {
        if ($location->employees()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete location with assigned employees');
        }

        $location->delete();

        return redirect()->route('locations.index')->with('status', 'Location deleted successfully');
    }

    public function checkRadius(Request $request, Location $location)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $isWithinRadius = $location->isWithinRadius(
            $request->latitude,
            $request->longitude
        );

        return response()->json([
            'within_radius' => $isWithinRadius,
            'location_name' => $location->name,
            'radius' => $location->radius,
        ]);
    }
}
