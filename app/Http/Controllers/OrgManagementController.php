<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrgManagementController extends Controller
{
    /**
     * Display the management dashboard for organization.
     */
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $divisions = Division::with('department')->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('organization.manage', compact('departments', 'divisions', 'locations'));
    }

    // --- Department Actions ---

    public function storeDept(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        Department::create($validated);

        return back()->with('success', 'Department created successfully.');
    }

    public function updateDept(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        $department->update($validated);

        return back()->with('success', 'Department updated successfully.');
    }

    public function destroyDept(Department $department)
    {
        // Check if there are employees or divisions under this department
        if (DB::table('employees')->where('department_id', $department->id)->exists() ||
            DB::table('divisions')->where('department_id', $department->id)->exists()) {
            return back()->with('error', 'Cannot delete department with active employees or divisions.');
        }

        $department->delete();

        return back()->with('success', 'Department deleted successfully.');
    }

    // --- Division Actions ---

    public function storeDiv(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        Division::create($validated);

        return back()->with('success', 'Division created successfully.');
    }

    public function updateDiv(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $division->update($validated);

        return back()->with('success', 'Division updated successfully.');
    }

    public function destroyDiv(Division $division)
    {
        // Check if there are employees under this division
        if (DB::table('employees')->where('division_id', $division->id)->exists()) {
            return back()->with('error', 'Cannot delete division with active employees.');
        }

        $division->delete();

        return back()->with('success', 'Division deleted successfully.');
    }

    // --- Location Actions ---

    public function storeLoc(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
        ]);

        Location::create($validated);

        return back()->with('success', 'Location created successfully.');
    }

    public function updateLoc(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
        ]);

        $location->update($validated);

        return back()->with('success', 'Location updated successfully.');
    }

    public function destroyLoc(Location $location)
    {
        // Check if there are employees or assets under this location
        if (DB::table('employees')->where('location_id', $location->id)->exists()) {
            return back()->with('error', 'Cannot delete location with active employees.');
        }

        $location->delete();

        return back()->with('success', 'Location deleted successfully.');
    }
}
