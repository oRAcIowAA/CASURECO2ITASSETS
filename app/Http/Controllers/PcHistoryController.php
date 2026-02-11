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
        if ($request->has('view') && $request->view == 'folders') {
            $branches = \App\Models\Branch::with(['departments.pcUnits.history.employee', 'departments.pcUnits.history.createdBy'])->get();
            return view('pc-history.folders', compact('branches'));
        }

        $history = PcHistory::with(['pcUnit', 'employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pc-history.index', compact('history'));
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

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
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