<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Aifut\ManagerMenuController;
use App\Http\Controllers\Admin\Aifut\ManagerMenuRuleController;
use App\Http\Controllers\Admin\Aifut\TenantBindingController;
use App\Http\Controllers\Aifut\AifutBridgeController;
use Illuminate\Support\Facades\Route;

if (! config('aifut.enabled')) {
    return;
}

Route::prefix('_aifut')
    ->name('aifut.')
    ->group(static function () {
        Route::get('manifest', [AifutBridgeController::class, 'manifest'])->name('manifest');

        Route::middleware(['auth', 'admin'])
            ->get('policy', [AifutBridgeController::class, 'policy'])
            ->name('policy');
    });

Route::middleware(['auth', 'updateUserActivity', 'admin'])
    ->prefix('dashboard/admin/aifut')
    ->name('dashboard.admin.aifut.')
    ->group(static function () {
        Route::get('manager-menu', [ManagerMenuController::class, 'index'])->name('manager-menu.index');
        Route::get('manager-menu/items/create', [ManagerMenuController::class, 'create'])->name('manager-menu.items.create');
        Route::post('manager-menu/items', [ManagerMenuController::class, 'store'])->name('manager-menu.items.store');
        Route::get('manager-menu/items/{menuItem}/edit', [ManagerMenuController::class, 'edit'])->name('manager-menu.items.edit');
        Route::put('manager-menu/items/{menuItem}', [ManagerMenuController::class, 'update'])->name('manager-menu.items.update');

        Route::get('manager-menu/rules', [ManagerMenuRuleController::class, 'index'])->name('manager-menu.rules.index');
        Route::get('manager-menu/rules/create', [ManagerMenuRuleController::class, 'create'])->name('manager-menu.rules.create');
        Route::post('manager-menu/rules', [ManagerMenuRuleController::class, 'store'])->name('manager-menu.rules.store');
        Route::get('manager-menu/rules/{rule}/edit', [ManagerMenuRuleController::class, 'edit'])->name('manager-menu.rules.edit');
        Route::put('manager-menu/rules/{rule}', [ManagerMenuRuleController::class, 'update'])->name('manager-menu.rules.update');

        Route::get('tenant-bindings', [TenantBindingController::class, 'index'])->name('tenant-bindings.index');
        Route::get('tenant-bindings/create', [TenantBindingController::class, 'create'])->name('tenant-bindings.create');
        Route::post('tenant-bindings', [TenantBindingController::class, 'store'])->name('tenant-bindings.store');
        Route::get('tenant-bindings/{tenantBinding}/edit', [TenantBindingController::class, 'edit'])->name('tenant-bindings.edit');
        Route::put('tenant-bindings/{tenantBinding}', [TenantBindingController::class, 'update'])->name('tenant-bindings.update');
    });
