<?php

namespace App\Http\Controllers\Admin\Aifut;

use App\Http\Controllers\Controller;
use App\Models\Aifut\AifutTenantBinding;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantBindingController extends Controller
{
    public function index(): View
    {
        return view('panel.admin.aifut.tenant-bindings.index', [
            'items' => AifutTenantBinding::query()->with('user')->orderByDesc('updated_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('panel.admin.aifut.tenant-bindings.form', [
            'item' => new AifutTenantBinding(),
            'users' => User::query()->orderBy('id')->limit(200)->get(['id', 'name', 'email']),
            'method' => 'POST',
            'action' => route('dashboard.admin.aifut.tenant-bindings.store'),
            'title' => __('Create Tenant Binding'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        AifutTenantBinding::query()->create($data);

        return redirect()->route('dashboard.admin.aifut.tenant-bindings.index')->with([
            'type' => 'success',
            'message' => __('Tenant binding created successfully.'),
        ]);
    }

    public function edit(AifutTenantBinding $tenantBinding): View
    {
        return view('panel.admin.aifut.tenant-bindings.form', [
            'item' => $tenantBinding,
            'users' => User::query()->orderBy('id')->limit(200)->get(['id', 'name', 'email']),
            'method' => 'PUT',
            'action' => route('dashboard.admin.aifut.tenant-bindings.update', $tenantBinding),
            'title' => __('Edit Tenant Binding'),
        ]);
    }

    public function update(Request $request, AifutTenantBinding $tenantBinding): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $tenantBinding->update($data);

        return redirect()->route('dashboard.admin.aifut.tenant-bindings.index')->with([
            'type' => 'success',
            'message' => __('Tenant binding updated successfully.'),
        ]);
    }

    protected function validateRequest(Request $request): array
    {
        $data = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'tenant_code' => 'required|string|max:191',
            'workspace_code' => 'nullable|string|max:191',
            'source_system' => 'required|string|max:100',
            'plan_code' => 'nullable|string|max:100',
            'storage_mode' => 'nullable|string|max:100',
            'domain_mode' => 'nullable|string|max:100',
            'capabilities' => 'nullable|string',
            'sync_meta' => 'nullable|string',
        ]);

        $data['capabilities'] = empty($data['capabilities']) ? null : json_decode($data['capabilities'], true);
        $data['sync_meta'] = empty($data['sync_meta']) ? null : json_decode($data['sync_meta'], true);
        $data['synced_at'] = now();

        return $data;
    }
}
