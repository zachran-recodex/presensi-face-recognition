<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function __construct()
    {
        // Only admin can access location management
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of locations
     */
    public function index(): View
    {
        $locations = Location::latest()->paginate(10);
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location
     */
    public function create(): View
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created location
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'required|integer|min:1|max:10000',
            'is_active' => 'boolean'
        ]);

        Location::create($validated);

        return redirect()->route('admin.locations.index')
            ->with('status', 'Location created successfully');
    }

    /**
     * Display the specified location
     */
    public function show(Location $location): View
    {
        $location->load(['attendances' => function($query) {
            $query->with('user')->latest()->take(10);
        }]);

        return view('admin.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified location
     */
    public function edit(Location $location): View
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified location
     */
    public function update(Request $request, Location $location): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'required|integer|min:1|max:10000',
            'is_active' => 'boolean'
        ]);

        $location->update($validated);

        return redirect()->route('admin.locations.index')
            ->with('status', 'Location updated successfully');
    }

    /**
     * Remove the specified location
     */
    public function destroy(Location $location): RedirectResponse
    {
        // Check if location has attendances
        if ($location->attendances()->count() > 0) {
            return redirect()->route('admin.locations.index')
                ->withErrors(['error' => 'Cannot delete location with existing attendance records']);
        }

        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('status', 'Location deleted successfully');
    }

    /**
     * Toggle location status
     */
    public function toggleStatus(Location $location): RedirectResponse
    {
        $location->update(['is_active' => !$location->is_active]);

        $status = $location->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.locations.index')
            ->with('status', "Location {$status} successfully");
    }
}
