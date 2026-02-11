<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PcUnitController;
use App\Http\Controllers\PcHistoryController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
require __DIR__ . '/auth.php';

// Protected Routes (require authentication)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
            return view('dashboard');
        }
        )->name('dashboard');

        // Profile Management
        Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

        // Branch Management
        Route::resource('branches', BranchController::class);

        // Department Management
        Route::resource('departments', DepartmentController::class);

        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // PC Unit Management
        Route::resource('pc-units', PcUnitController::class);

        // PC Unit Assignment Actions
        Route::post('pc-units/{pcUnit}/assign', [PcUnitController::class , 'assign'])->name('pc-units.assign');
        Route::post('pc-units/{pcUnit}/return', [PcUnitController::class , 'return'])->name('pc-units.return');

        // Lifecycle Routes
        Route::get('pc-units/{pcUnit}/dispose', [PcUnitController::class , 'dispose'])->name('pc-units.dispose');
        Route::post('pc-units/{pcUnit}/condemn', [PcUnitController::class , 'condemn'])->name('pc-units.condemn');
        Route::get('pc-units/{pcUnit}/transfer', [PcUnitController::class , 'transfer'])->name('pc-units.transfer');
        Route::post('pc-units/{pcUnit}/reassign', [PcUnitController::class , 'reassign'])->name('pc-units.reassign');

        // Printing Routes
        Route::get('reports', [\App\Http\Controllers\ReportController::class , 'index'])->name('reports.index');
        Route::get('pc-units/{pcUnit}/print-mr', [\App\Http\Controllers\ReportController::class , 'printMr'])->name('pc-units.print-mr');
        Route::get('pc-units/{pcUnit}/print-condemn', [\App\Http\Controllers\ReportController::class , 'printCondemn'])->name('pc-units.print-condemn');

        // PC History Management
        Route::get('pc-history', [PcHistoryController::class , 'index'])->name('pc-history.index');
        Route::get('pc-history/pc/{pcUnitId}', [PcHistoryController::class , 'showByPc'])->name('pc-history.show-by-pc');
        Route::get('pc-history/employee/{employeeId}', [PcHistoryController::class , 'showByEmployee'])->name('pc-history.show-by-employee');
        Route::get('pc-history/report', [PcHistoryController::class , 'report'])->name('pc-history.report');
        // Organization Chart
        Route::get('/organization', [App\Http\Controllers\OrganizationController::class , 'index'])->name('organization.index');
    });