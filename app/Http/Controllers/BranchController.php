<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // all branches
    public function index()
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }

    // create form
    public function create()
    {
        return view('branches.create');
    }

    // new branch
    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Branch::create([
            'branch_name' => $request->branch_name,
            'location' => $request->location,
        ]);

        return redirect()->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    // branch details
    public function show(Branch $branch)
    {
        $branch->load('departments', 'employees');
        $pcUnits = \App\Models\PcUnit::where('branch_id', $branch->id)
            ->with(['department', 'employee'])
            ->latest()
            ->get();

        return view('branches.show', compact('branch', 'pcUnits'));
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->departments()->count() > 0) {
            return back()->with('error', 'Cannot delete branch with existing departments.');
        }

        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}