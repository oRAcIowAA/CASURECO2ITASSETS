<?php

namespace App\Http\Controllers;

use App\Models\PowerUtilityHistory;
use App\Models\PowerUtility;
use Illuminate\Http\Request;

class PowerUtilityHistoryController extends Controller
{
    /**
     * Display Power Utility assignment history
     */
    public function index(Request $request)
    {
        $query = PowerUtilityHistory::query();

        // Filter by generic search (Asset Tag, Type, Brand/Model, Employee Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('powerUtility', function ($q2) use ($search) {
                        $q2->where('asset_tag', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%");
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

        $history = $query->with(['powerUtility', 'employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('power-utility-history.index', compact('history'));
    }

    /**
     * Show history for specific Power Utility
     */
    public function showByPowerUtility($powerUtilityId)
    {
        $powerUtility = PowerUtility::findOrFail($powerUtilityId);
        $history = PowerUtilityHistory::where('power_utility_id', $powerUtilityId)
            ->with(['employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('power-utility-history.show', compact('powerUtility', 'history'));
    }
}
