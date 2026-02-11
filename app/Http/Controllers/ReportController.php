<?php

namespace App\Http\Controllers;

use App\Models\PcUnit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display listing of reports.
     */
    public function index()
    {
        $pcUnits = PcUnit::with(['employee', 'department', 'branch'])
            ->whereNotNull('employee_id')
            ->orWhereIn('status', ['condemned', 'defective'])
            ->latest()
            ->paginate(20);

        return view('reports.index', compact('pcUnits'));
    }

    /**
     * Generate Memorandum Receipt (MR) / Property Acknowledgment Receipt (PAR)
     */
    public function printMr(PcUnit $pcUnit)
    {
        if (!$pcUnit->employee) {
            return back()->with('error', 'Cannot generate MR for unassigned unit.');
        }

        try {
            $pdf = Pdf::loadView('reports.mr', compact('pcUnit'));
            return $pdf->stream('MR-' . $pcUnit->asset_tag . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return view('reports.mr', compact('pcUnit'));
        }
    }

    /**
     * Generate Waste Material Report (condemned units)
     */
    public function printCondemn(PcUnit $pcUnit)
    {
        if (!in_array($pcUnit->status, ['condemned', 'defective'])) {
            return back()->with('error', 'Unit is not marked as condemned or defective.');
        }

        try {
            $pdf = Pdf::loadView('reports.condemn', compact('pcUnit'));
            return $pdf->stream('Waste-Report-' . $pcUnit->asset_tag . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return view('reports.condemn', compact('pcUnit'));
        }
    }
}
