<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use App\Services\FaceRecognitionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    private FaceRecognitionService $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request): View
    {
        $query = User::query()->with(['attendances'])->where('role', '!=', 'super_admin');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by face enrollment status
        if ($request->filled('face_enrolled')) {
            $query->where('is_face_enrolled', $request->face_enrolled === 'yes');
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        // Get statistics (excluding super admin)
        $stats = [
            'total_users' => User::where('role', '!=', 'super_admin')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'regular_users' => User::where('role', 'user')->count(),
            'face_enrolled' => User::where('is_face_enrolled', true)->where('role', '!=', 'super_admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        $locations = Location::active()->get();

        return view('admin.users.create', compact('locations'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'employee_id' => ['nullable', 'string', 'max:50', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,user'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('status', 'User created successfully');
    }

    /**
     * Display the specified user
     */
    public function show(User $user): View
    {
        $user->load([
            'attendances' => function ($query) {
                $query->with('location')->latest()->take(10);
            },
            'assignedLocation',
        ]);

        // Get user statistics
        $stats = [
            'total_attendances' => $user->attendances()->count(),
            'this_month_attendances' => $user->attendances()->thisMonth()->count(),
            'check_ins' => $user->attendances()->where('type', 'check_in')->count(),
            'check_outs' => $user->attendances()->where('type', 'check_out')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        $locations = Location::active()->get();

        return view('admin.users.edit', compact('user', 'locations'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'employee_id' => ['nullable', 'string', 'max:50', 'unique:users,employee_id,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,user'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('status', 'User updated successfully');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deletion of users with attendance records
        if ($user->attendances()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => 'Cannot delete user with existing attendance records']);
        }

        // Prevent deletion of the last super admin user
        if ($user->isSuperAdmin() && User::where('role', 'super_admin')->count() === 1) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => 'Cannot delete the last super admin user']);
        }

        // Prevent deletion of the last admin user (if no super admin exists)
        if ($user->isRegularAdmin() && User::where('role', 'admin')->count() === 1 && User::where('role', 'super_admin')->count() === 0) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => 'Cannot delete the last admin user']);
        }

        // Prevent users from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->withErrors(['error' => 'You cannot delete your own account']);
        }

        // Delete face enrollment if exists
        if ($user->is_face_enrolled) {
            try {
                $userId = $user->employee_id ?: $user->id;
                $this->faceService->deleteFace((string) $userId);
            } catch (\Exception $e) {
                // Log error but continue with user deletion
                \Log::warning('Failed to delete face enrollment for user '.$user->id.': '.$e->getMessage());
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'User deleted successfully');
    }

    /**
     * Reset user's face enrollment
     */
    public function resetFaceEnrollment(User $user): RedirectResponse
    {
        if (! $user->is_face_enrolled) {
            return redirect()->route('admin.users.show', $user)
                ->withErrors(['error' => 'User does not have face enrollment to reset']);
        }

        try {
            // Delete from face recognition service
            $userId = $user->employee_id ?: $user->id;
            $this->faceService->deleteFace((string) $userId);

            // Update user record
            $user->update([
                'face_image' => null,
                'is_face_enrolled' => false,
            ]);

            return redirect()->route('admin.users.show', $user)
                ->with('status', 'Face enrollment reset successfully. User will need to enroll again.');

        } catch (\Exception $e) {
            return redirect()->route('admin.users.show', $user)
                ->withErrors(['error' => 'Failed to reset face enrollment: '.$e->getMessage()]);
        }
    }
}
