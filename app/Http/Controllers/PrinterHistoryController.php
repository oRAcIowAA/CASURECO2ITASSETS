<?php

namespace App\Http\Controllers;

use App\Models\PrinterHistory;
use App\Models\Printer;
use Illuminate\Http\Request;

class PrinterHistoryController extends Controller
{
    /**
     * Display Printer assignment history
     */
    public function index(Request $request)
    {
        $query = PrinterHistory::query();

        // Filter by generic search (Printer Brand/Model, Employee Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('printer', function ($q2) use ($search) {
                        $q2->where('brand', 'like', "%{$search}%")
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

        $history = $query->with(['printer', 'employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('printer-history.index', compact('history'));
    }

    /**
     * Show history for specific Printer
     */
    public function showByPrinter($printerId)
    {
        $printer = Printer::findOrFail($printerId);
        $history = PrinterHistory::where('printer_id', $printerId)
            ->with(['employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('printer-history.show', compact('printer', 'history'));
    }
}
