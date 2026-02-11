<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $branches = \App\Models\Branch::with(['departments.employees' => function ($query) {
            $query->orderBy('full_name');
        }])->get();

        return view('organization.index', compact('branches'));
    }
}
