<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $groupsConst = \App\Constants\Organization::GROUPS;

        // Fetch all employees with PC Units, Printers, and Network Devices, ordered by name
        $allEmployees = Employee::with(['pcUnits', 'printers', 'networkDevices'])->orderBy('full_name')->get();

        $groups = collect();

        foreach ($groupsConst as $groupName) {
            $empsInGroup = $allEmployees->filter(function ($employee) use ($groupName) {
                return $employee->group == $groupName;
            });

            if ($empsInGroup->isEmpty())
                continue;

            $groupDepts = collect();

            $groupedByDept = $empsInGroup->groupBy(function ($emp) {
                return $emp->department ?: 'Other Departments';
            });

            foreach ($groupedByDept as $deptName => $deptEmployees) {
                $deptDivisions = collect();

                $groupedByDivision = $deptEmployees->groupBy(function ($emp) {
                    return $emp->division ?: 'Other Divisions';
                });

                foreach ($groupedByDivision as $divisionName => $divEmployees) {
                    $deptDivisions->push((object)[
                        'id' => Str::slug($groupName . '-' . $deptName . '-' . $divisionName),
                        'division_name' => $divisionName,
                        'employees' => $divEmployees
                    ]);
                }

                // Sort divisions alphabetically
                $deptDivisions = $deptDivisions->sortBy('division_name')->values();

                $groupDepts->push((object)[
                    'id' => Str::slug($groupName . '-' . $deptName),
                    'department_name' => $deptName,
                    'divisions' => $deptDivisions
                ]);
            }

            // Sort departments alphabetically
            $groupDepts = $groupDepts->sortBy('department_name')->values();

            $groups->push((object)[
                'id' => Str::slug($groupName),
                'group_name' => $groupName,
                'departments' => $groupDepts
            ]);
        }

        return view('organization.index', compact('groups'));
    }
}
