<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PcUnitController;
use App\Http\Controllers\PcHistoryController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\NetworkDeviceController;
use App\Http\Controllers\MobileDeviceController;
use App\Http\Controllers\MobileDeviceHistoryController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
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

            $powerHistory = \App\Models\PowerUtilityHistory::with(['powerUtility', 'employee', 'createdBy'])
                ->latest()->take(5)->get()
                ->map(function ($h) {
                $h->device_type_label = 'Power Utility';
                $h->device = $h->powerUtility;
                $h->device_name = $h->powerUtility ? $h->powerUtility->asset_tag . ' (' . $h->powerUtility->type . ')' : 'Unknown';
                return $h;
            }
            );

            $mobileHistory = \App\Models\MobileDeviceHistory::with(['mobileDevice', 'employee', 'createdBy'])
                ->latest()->take(5)->get()
                ->map(function ($h) {
                $h->device_type_label = 'Mobile Device';
                $h->device = $h->mobileDevice;
                $h->device_name = $h->mobileDevice ? $h->mobileDevice->asset_tag . ' (' . $h->mobileDevice->brand . ' ' . $h->mobileDevice->model . ')' : 'Unknown';
                return $h;
            }
            );

            $recentActivities = $pcHistory->concat($printerHistory)->concat($networkHistory)->concat($powerHistory)->concat($mobileHistory)
                ->sortByDesc('created_at')
                ->take(5);

            // Dashboard Stats
            $pcCount = \App\Models\PcUnit::count();
            $printerCount = \App\Models\Printer::count();
            $networkCount = \App\Models\NetworkDevice::count();
            $powerCount = \App\Models\PowerUtility::count();
            $mobileCount = \App\Models\MobileDevice::count();
            $employeeCount = \App\Models\Employee::count();
            
            $totalAssets = $pcCount + $printerCount + $networkCount + $powerCount + $mobileCount;
            
            $availableCount = \App\Models\PcUnit::where('status', 'available')->count() +
                             \App\Models\Printer::where('status', 'available')->count() +
                             \App\Models\NetworkDevice::where('status', 'available')->count() +
                             \App\Models\PowerUtility::where('status', 'available')->count() +
                             \App\Models\MobileDevice::where('status', 'available')->count();

            return view('dashboard', compact(
                'recentActivities', 
                'pcCount', 
                'printerCount', 
                'networkCount', 
                'powerCount',
                'mobileCount',
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

        // Power Utility Management
        Route::resource('power-utilities', \App\Http\Controllers\PowerUtilityController::class);
        Route::post('power-utilities/{powerUtility}/assign', [\App\Http\Controllers\PowerUtilityController::class, 'assign'])->name('power-utilities.assign');
        Route::post('power-utilities/{powerUtility}/return', [\App\Http\Controllers\PowerUtilityController::class, 'return'])->name('power-utilities.return');
        Route::get('power-utilities/{powerUtility}/dispose', [\App\Http\Controllers\PowerUtilityController::class, 'dispose'])->name('power-utilities.dispose');
        Route::post('power-utilities/{powerUtility}/condemn', [\App\Http\Controllers\PowerUtilityController::class, 'condemn'])->name('power-utilities.condemn');
        Route::post('power-utilities/{powerUtility}/repair', [\App\Http\Controllers\PowerUtilityController::class, 'repair'])->name('power-utilities.repair');
                Route::get('power-utilities/{powerUtility}/transfer', [\App\Http\Controllers\PowerUtilityController::class, 'transfer'])->name('power-utilities.transfer');
                Route::post('power-utilities/{powerUtility}/reassign', [\App\Http\Controllers\PowerUtilityController::class, 'reassign'])->name('power-utilities.reassign');
        Route::post('power-utilities/{powerUtility}/restore', [\App\Http\Controllers\PowerUtilityController::class, 'restore'])->name('power-utilities.restore');
        Route::get('power-utilities/{powerUtility}/print-label', [\App\Http\Controllers\PowerUtilityController::class, 'printLabel'])->name('power-utilities.print-label');

        // Mobile Device Management
        Route::resource('mobile-devices', MobileDeviceController::class);
        Route::post('mobile-devices/{mobileDevice}/assign', [MobileDeviceController::class, 'assign'])->name('mobile-devices.assign');
        Route::post('mobile-devices/{mobileDevice}/return', [MobileDeviceController::class, 'return'])->name('mobile-devices.return');
        Route::get('mobile-devices/{mobileDevice}/dispose', [MobileDeviceController::class, 'dispose'])->name('mobile-devices.dispose');
        Route::post('mobile-devices/{mobileDevice}/condemn', [MobileDeviceController::class, 'condemn'])->name('mobile-devices.condemn');
        Route::post('mobile-devices/{mobileDevice}/repair', [MobileDeviceController::class, 'repair'])->name('mobile-devices.repair');
        Route::get('mobile-devices/{mobileDevice}/transfer', [MobileDeviceController::class, 'transfer'])->name('mobile-devices.transfer');
        Route::post('mobile-devices/{mobileDevice}/reassign', [MobileDeviceController::class, 'reassign'])->name('mobile-devices.reassign');
        Route::post('mobile-devices/{mobileDevice}/restore', [MobileDeviceController::class, 'restore'])->name('mobile-devices.restore');
        Route::get('mobile-devices/{mobileDevice}/print-label', [MobileDeviceController::class, 'printLabel'])->name('mobile-devices.print-label');

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
        Route::get('power-utility-history', [\App\Http\Controllers\PowerUtilityHistoryController::class , 'index'])->name('power-utility-history.index');
        Route::get('power-utility-history/{powerUtility}', [\App\Http\Controllers\PowerUtilityHistoryController::class , 'showByPowerUtility'])->name('power-utility-history.show');
        Route::get('mobile-device-history', [MobileDeviceHistoryController::class, 'index'])->name('mobile-device-history.index');
        Route::get('mobile-device-history/{mobileDevice}', [MobileDeviceHistoryController::class, 'showByDevice'])->name('mobile-device-history.show');

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

        // Organization Management
        Route::get('/organization/manage', [App\Http\Controllers\OrgManagementController::class, 'index'])->name('organization.manage');
        
        // Department CRUD
        Route::post('/organization/departments', [App\Http\Controllers\OrgManagementController::class, 'storeDept'])->name('organization.departments.store');
        Route::patch('/organization/departments/{department}', [App\Http\Controllers\OrgManagementController::class, 'updateDept'])->name('organization.departments.update');
        Route::delete('/organization/departments/{department}', [App\Http\Controllers\OrgManagementController::class, 'destroyDept'])->name('organization.departments.destroy');
        
        // Division CRUD
        Route::post('/organization/divisions', [App\Http\Controllers\OrgManagementController::class, 'storeDiv'])->name('organization.divisions.store');
        Route::patch('/organization/divisions/{division}', [App\Http\Controllers\OrgManagementController::class, 'updateDiv'])->name('organization.divisions.update');
        Route::delete('/organization/divisions/{division}', [App\Http\Controllers\OrgManagementController::class, 'destroyDiv'])->name('organization.divisions.destroy');
        
        // Location CRUD
        Route::post('/organization/locations', [App\Http\Controllers\OrgManagementController::class, 'storeLoc'])->name('organization.locations.store');
        Route::patch('/organization/locations/{location}', [App\Http\Controllers\OrgManagementController::class, 'updateLoc'])->name('organization.locations.update');
        Route::delete('/organization/locations/{location}', [App\Http\Controllers\OrgManagementController::class, 'destroyLoc'])->name('organization.locations.destroy');

        // Organization Chart
        Route::get('/organization', [App\Http\Controllers\OrganizationController::class , 'index'])->name('organization.index');

    });