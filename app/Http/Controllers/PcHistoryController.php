<?php

namespace App\Http\Controllers;

use App\Models\PcHistory;
use App\Models\PcUnit;
use App\Models\Employee;
use Illuminate\Http\Request;

class PcHistoryController extends Controller
{
    /**
     * Display PC assignment history
     */
    public function index(Request $request)
    {
        $query = PcHistory::query();

        $query->with(['pcUnit', 'employee', 'createdBy', 'previousEmployee']);

        // Filter by Group (current location of the unit)
        if ($request->filled('group')) {
            $query->whereHas('pcUnit', function ($q) use ($request) {
                $q->where('group', $request->group);
            });
        }

        // Filter by Division (current location of the unit)
        if ($request->filled('division')) {
            $query->whereHas('pcUnit', function ($q) use ($request) {
                $q->where('division', $request->division);
            });
        }

        // Filter by Department (current location of the unit)
        if ($request->filled('department')) {
            $query->whereHas('pcUnit', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        // Filter by generic search (Asset Tag, Employee Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pcUnit', function ($q2) use ($search) {
                        $q2->where('asset_tag', 'like', "%{$search}%");
                    }
                    )->orWhereHas('employee', function ($q3) use ($search) {
                        $q3->where('full_name', 'like', "%{$search}%");
                    }
                    );
                });
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $history = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;

        return view('pc-history.index', compact('history', 'groups', 'divisions', 'departments'));
    }

    /**
     * Show history for specific PC
     */
    public function showByPc($pcUnitId)
    {
        $pcUnit = PcUnit::findOrFail($pcUnitId);
        $history = PcHistory::where('pc_unit_id', $pcUnitId)
            ->with(['employee', 'previousEmployee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pc-history.show', compact('pcUnit', 'history'));
    }

    /**
     * Show history for specific employee
     */
    public function showByEmployee($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $history = PcHistory::where('employee_id', $employeeId)
            ->with(['pcUnit', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pc-history.employee', compact('employee', 'history'));
    }

    /**
     * Generate history report
     */
    public function report(Request $request)
    {
        $query = PcHistory::query();

        // Filter by generic search (Asset Tag, Employee Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pcUnit', function ($q2) use ($search) {
                        $q2->where('asset_tag', 'like', "%{$search}%");
                    }
                    )->orWhereHas('employee', function ($q3) use ($search) {
                        $q3->where('full_name', 'like', "%{$search}%");
                    }
                    );
                });
        }
        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $history = $query->with(['pcUnit', 'employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pc-history.report', compact('history'));
    }
}