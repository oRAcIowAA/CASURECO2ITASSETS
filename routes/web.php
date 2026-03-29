<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PcUnitController;
use App\Http\Controllers\PcHistoryController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\NetworkDeviceController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/scan/{uuid}', [\App\Http\Controllers\PublicAssetController::class , 'show'])->name('public.asset.show');

// Authentication Routes
require __DIR__ . '/auth.php';

// Protected Routes (require authentication)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
            $pcHistory = \App\Models\PcHistory::with(['pcUnit', 'employee', 'createdBy'])
                ->latest()->take(5)->get()
                ->map(function ($h) {
                $h->device_type_label = 'PC Unit';
                $h->device = $h->pcUnit;
                $h->device_name = $h->pcUnit ? $h->pcUnit->asset_tag : 'Unknown';
                return $h;
            }
            );

            $printerHistory = \App\Models\PrinterHistory::with(['printer', 'employee', 'createdBy'])
                ->latest()->take(5)->get()
                ->map(function ($h) {
                $h->device_type_label = 'Printer';
                $h->device = $h->printer;
                $h->device_name = $h->printer ? $h->printer->brand . ' ' . $h->printer->model : 'Unknown';
                return $h;
            }
            );

            $networkHistory = \App\Models\NetworkDeviceHistory::with(['networkDevice', 'employee', 'createdBy'])
                ->latest()->take(5)->get()
                ->map(function ($h) {
                $h->device_type_label = 'Network Device';
                $h->device = $h->networkDevice;
                $h->device_name = $h->networkDevice ? $h->networkDevice->brand . ' ' . $h->networkDevice->model : 'Unknown';
                return $h;
            }
            );

            $recentActivities = $pcHistory->concat($printerHistory)->concat($networkHistory)
                ->sortByDesc('created_at')
                ->take(5);

            // Dashboard Stats
            $pcCount = \App\Models\PcUnit::count();
            $printerCount = \App\Models\Printer::count();
            $networkCount = \App\Models\NetworkDevice::count();
            $employeeCount = \App\Models\Employee::count();
            
            $totalAssets = $pcCount + $printerCount + $networkCount;
            
            $availableCount = \App\Models\PcUnit::where('status', 'available')->count() +
                             \App\Models\Printer::where('status', 'available')->count() +
                             \App\Models\NetworkDevice::where('status', 'available')->count();

            return view('dashboard', compact(
                'recentActivities', 
                'pcCount', 
                'printerCount', 
                'networkCount', 
                'employeeCount', 
                'totalAssets', 
                'availableCount'
            ));
        }
        )->name('dashboard');

        // Profile Management
        Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // Admin Management
        Route::resource('admins', \App\Http\Controllers\AdminController::class)->only(['index', 'create', 'store', 'edit', 'update']);

        // PC Unit Management
        Route::resource('pc-units', PcUnitController::class);
        Route::post('pc-units/{pcUnit}/assign', [PcUnitController::class , 'assign'])->name('pc-units.assign');
        Route::post('pc-units/{pcUnit}/repair', [PcUnitController::class , 'repair'])->name('pc-units.repair');
        Route::post('pc-units/{pcUnit}/restore', [PcUnitController::class , 'restore'])->name('pc-units.restore');

        // Printer Management
        Route::resource('printers', PrinterController::class);
        Route::post('printers/{printer}/reassign', [PrinterController::class , 'reassign'])->name('printers.reassign');
        Route::post('printers/{printer}/return', [PrinterController::class , 'returnPrinter'])->name('printers.return');
        Route::get('printers/{printer}/transfer', [PrinterController::class , 'transfer'])->name('printers.transfer');
        Route::get('printers/{printer}/dispose', [PrinterController::class , 'dispose'])->name('printers.dispose');
        Route::post('printers/{printer}/condemn', [PrinterController::class , 'condemn'])->name('printers.condemn');
        Route::post('printers/{printer}/repair', [PrinterController::class , 'repair'])->name('printers.repair');
        Route::get('printers/{printer}/print-label', [PrinterController::class , 'printLabel'])->name('printers.print-label');
        Route::get('printers/{printer}/print-mr', [PrinterController::class , 'printMr'])->name('printers.print-mr');
        Route::get('printers/{printer}/print-condemn', [PrinterController::class , 'printCondemn'])->name('printers.print-condemn');
        Route::get('printers/{printer}/print-disposal', [PrinterController::class , 'printDisposal'])->name('printers.print-disposal');
        Route::post('printers/{printer}/restore', [PrinterController::class , 'restore'])->name('printers.restore');

        // Networking Devices Management
        Route::resource('network-devices', NetworkDeviceController::class);
        Route::post('network-devices/{networkDevice}/reassign', [NetworkDeviceController::class , 'reassign'])->name('network-devices.reassign');
        Route::post('network-devices/{networkDevice}/return', [NetworkDeviceController::class , 'returnDevice'])->name('network-devices.return');
        Route::get('network-devices/{networkDevice}/transfer', [NetworkDeviceController::class , 'transfer'])->name('network-devices.transfer');
        Route::get('network-devices/{networkDevice}/dispose', [NetworkDeviceController::class , 'dispose'])->name('network-devices.dispose');
        Route::post('network-devices/{networkDevice}/condemn', [NetworkDeviceController::class , 'condemn'])->name('network-devices.condemn');
        Route::post('network-devices/{networkDevice}/repair', [NetworkDeviceController::class , 'repair'])->name('network-devices.repair');
        Route::get('network-devices/{networkDevice}/print-label', [NetworkDeviceController::class , 'printLabel'])->name('network-devices.print-label');
        Route::get('network-devices/{networkDevice}/print-mr', [NetworkDeviceController::class , 'printMr'])->name('network-devices.print-mr');
        Route::get('network-devices/{networkDevice}/print-condemn', [NetworkDeviceController::class , 'printCondemn'])->name('network-devices.print-condemn');
        Route::get('network-devices/{networkDevice}/print-disposal', [NetworkDeviceController::class , 'printDisposal'])->name('network-devices.print-disposal');
        Route::post('network-devices/{networkDevice}/restore', [NetworkDeviceController::class , 'restore'])->name('network-devices.restore');

        // PC Unit De-assignment
        Route::post('pc-units/{pcUnit}/return', [PcUnitController::class , 'return'])->name('pc-units.return');

        // PC Unit Lifecycle Actions
        Route::get('pc-units/{pcUnit}/dispose', [PcUnitController::class , 'dispose'])->name('pc-units.dispose');
        Route::post('pc-units/{pcUnit}/condemn', [PcUnitController::class , 'condemn'])->name('pc-units.condemn');
        Route::get('pc-units/{pcUnit}/transfer', [PcUnitController::class , 'transfer'])->name('pc-units.transfer');
        Route::post('pc-units/{pcUnit}/reassign', [PcUnitController::class , 'reassign'])->name('pc-units.reassign');
        Route::get('pc-units/{pcUnit}/print-label', [PcUnitController::class , 'printLabel'])->name('pc-units.print-label');
        Route::get('pc-units/{pcUnit}/print-disposal', [PcUnitController::class , 'printDisposal'])->name('pc-units.print-disposal');

        // Parts Management
        Route::get('parts', [\App\Http\Controllers\PartsController::class, 'index'])->name('parts.index');
        Route::put('parts/{type}/{id}', [\App\Http\Controllers\PartsController::class, 'update'])->name('parts.update');

        // Printing Routes
        Route::get('reports', [\App\Http\Controllers\ReportController::class , 'index'])->name('reports.index');
        Route::get('reports/department', [\App\Http\Controllers\ReportController::class, 'department'])->name('reports.department');
        Route::get('reports/print-department', [\App\Http\Controllers\ReportController::class, 'printDepartment'])->name('reports.print-department');
        Route::get('reports/print-list', [\App\Http\Controllers\ReportController::class , 'printList'])->name('reports.print-list');
        Route::get('pc-units/{pcUnit}/print-mr', [\App\Http\Controllers\ReportController::class , 'printMr'])->name('pc-units.print-mr');
        Route::get('pc-units/{pcUnit}/print-condemn', [\App\Http\Controllers\ReportController::class , 'printCondemn'])->name('pc-units.print-condemn');

        // Printer History
        Route::get('printer-history', [\App\Http\Controllers\PrinterHistoryController::class , 'index'])->name('printer-history.index');
        Route::get('printer-history/{printer}', [\App\Http\Controllers\PrinterHistoryController::class , 'showByPrinter'])->name('printer-history.show');

        // Network Device History
        Route::get('network-device-history', [\App\Http\Controllers\NetworkDeviceHistoryController::class , 'index'])->name('network-device-history.index');
        Route::get('network-device-history/{networkDevice}', [\App\Http\Controllers\NetworkDeviceHistoryController::class , 'showByDevice'])->name('network-device-history.show');

        // Asset History & Activity Log
        Route::get('activities', [\App\Http\Controllers\AssetHistoryController::class, 'index'])->name('activities.index');
        Route::get('pc-history', [PcHistoryController::class , 'index'])->name('pc-history.index');
        Route::get('pc-history/pc/{pcUnitId}', [PcHistoryController::class , 'showByPc'])->name('pc-history.show-by-pc');
        Route::get('pc-history/employee/{employeeId}', [PcHistoryController::class , 'showByEmployee'])->name('pc-history.show-by-employee');
        Route::get('pc-history/report', [PcHistoryController::class , 'report'])->name('pc-history.report');
        // QR Asset Management
        Route::get('qr-assets', [\App\Http\Controllers\QrAssetController::class, 'index'])->name('qr-assets.index');
        Route::post('qr-assets/print', [\App\Http\Controllers\QrAssetController::class, 'printLabels'])->name('qr-assets.print');
        Route::post('qr-assets/download', [\App\Http\Controllers\QrAssetController::class, 'downloadLabels'])->name('qr-assets.download');

        // Organization Chart
        Route::get('/organization', [App\Http\Controllers\OrganizationController::class , 'index'])->name('organization.index');
    });