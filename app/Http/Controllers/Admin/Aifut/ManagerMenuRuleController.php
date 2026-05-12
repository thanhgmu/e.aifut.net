<?php

namespace App\Http\Controllers\Admin\Aifut;

use App\Http\Controllers\Controller;
use App\Models\Aifut\AifutMenuItem;
use App\Models\Aifut\AifutMenuRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManagerMenuRuleController extends Controller
{
    public function index(): View
    {
        return view('panel.admin.aifut.manager-menu.rules.index', [
            'items' => AifutMenuRule::query()->with('menuItem')->orderByDesc('updated_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('panel.admin.aifut.manager-menu.rules.form', [
            'item' => new AifutMenuRule(),
            'menuItems' => AifutMenuItem::query()->orderBy('code')->get(['id', 'code', 'title']),
            'method' => 'POST',
            'action' => route('dashboard.admin.aifut.manager-menu.rules.store'),
            'title' => __('Create AIFUT Menu Rule'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        AifutMenuRule::query()->create($data);

        return redirect()->route('dashboard.admin.aifut.manager-menu.rules.index')->with([
            'type' => 'success',
            'message' => __('AIFUT menu rule created successfully.'),
        ]);
    }

    public function edit(AifutMenuRule $rule): View
    {
        return view('panel.admin.aifut.manager-menu.rules.form', [
            'item' => $rule,
            'menuItems' => AifutMenuItem::query()->orderBy('code')->get(['id', 'code', 'title']),
            'method' => 'PUT',
            'action' => route('dashboard.admin.aifut.manager-menu.rules.update', $rule),
            'title' => __('Edit AIFUT Menu Rule'),
        ]);
    }

    public function update(Request $request, AifutMenuRule $rule): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $rule->update($data);

        return redirect()->route('dashboard.admin.aifut.manager-menu.rules.index')->with([
            'type' => 'success',
            'message' => __('AIFUT menu rule updated successfully.'),
        ]);
    }

    protected function validateRequest(Request $request): array
    {
        $data = $request->validate([
            'menu_item_id' => 'required|integer|exists:aifut_menu_items,id',
            'scope_type' => 'required|string|max:50',
            'scope_key' => 'nullable|string|max:191',
            'actor_role' => 'required|string|max:50',
            'plan_code' => 'nullable|string|max:100',
            'feature_code' => 'nullable|string|max:100',
            'storage_mode' => 'nullable|string|max:100',
            'domain_mode' => 'nullable|string|max:100',
            'source_system' => 'required|string|max:100',
            'is_visible' => 'nullable|boolean',
            'is_enabled' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'conditions' => 'nullable|string',
        ]);

        $data['is_visible'] = $request->boolean('is_visible');
        $data['is_enabled'] = $request->boolean('is_enabled');
        $data['conditions'] = empty($data['conditions']) ? null : json_decode($data['conditions'], true);

        return $data;
    }
}
