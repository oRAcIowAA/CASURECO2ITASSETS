<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $groupsConst = \Illuminate\Support\Facades\DB::table('locations')->pluck('name')->toArray();

        // Fetch all employees with PC Units, Printers, Network Devices, Power Utilities, and Mobile Devices, ordered by name
        $allEmployees = Employee::with(['pcUnits', 'printers', 'networkDevices', 'powerUtilities', 'mobileDevices'])->orderBy('lname')->orderBy('fname')->get();

        $groups = collect();

        foreach ($groupsConst as $groupName) {
            $empsInGroup = $allEmployees->filter(function ($employee) use ($groupName) {
                return trim($employee->group) == trim($groupName);
            });

            if ($empsInGroup->isEmpty())
                continue;

            $groupDepts = collect();

            $groupedByDept = $empsInGroup->groupBy(function ($emp) {
                return $emp->department ? trim($emp->department) : 'NO DEPARTMENT';
            });

            foreach ($groupedByDept as $deptName => $deptEmployees) {
                $deptDivisions = collect();

                $groupedByDivision = $deptEmployees->groupBy(function ($emp) {
                    return $emp->division ? trim($emp->division) : 'NO DIVISION';
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
