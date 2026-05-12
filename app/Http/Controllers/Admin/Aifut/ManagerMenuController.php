<?php

namespace App\Http\Controllers\Admin\Aifut;

use App\Http\Controllers\Controller;
use App\Models\Aifut\AifutChangeRequest;
use App\Models\Aifut\AifutMenuItem;
use App\Models\Aifut\AifutMenuRule;
use App\Models\Aifut\AifutTenantBinding;
use App\Services\Aifut\MenuResolverService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ManagerMenuController extends Controller
{
    public function __construct(
        protected MenuResolverService $menuResolverService
    ) {
    }

    public function index(): View
    {
        $tablesReady = Schema::hasTable('aifut_menu_items')
            && Schema::hasTable('aifut_menu_rules')
            && Schema::hasTable('aifut_tenant_bindings')
            && Schema::hasTable('aifut_change_requests');

        return view('panel.admin.aifut.manager-menu.index', [
            'tablesReady' => $tablesReady,
            'stats' => $tablesReady ? [
                'menu_items' => AifutMenuItem::query()->count(),
                'menu_rules' => AifutMenuRule::query()->count(),
                'tenant_bindings' => AifutTenantBinding::query()->count(),
                'change_requests' => AifutChangeRequest::query()->count(),
            ] : [
                'menu_items' => 0,
                'menu_rules' => 0,
                'tenant_bindings' => 0,
                'change_requests' => 0,
            ],
            'items' => $tablesReady ? AifutMenuItem::query()->orderBy('actor_role')->orderBy('sort_order')->get() : collect(),
            'rules' => $tablesReady ? AifutMenuRule::query()->with('menuItem')->orderByDesc('updated_at')->limit(10)->get() : collect(),
            'preview' => $tablesReady ? $this->menuResolverService->preview() : [
                'superadmin' => [],
                'admin' => [],
                'user' => [],
            ],
        ]);
    }

    public function create(): View
    {
        return view('panel.admin.aifut.manager-menu.items.form', [
            'item' => new AifutMenuItem(),
            'menuParents' => AifutMenuItem::query()->orderBy('code')->get(['id', 'code', 'title']),
            'method' => 'POST',
            'action' => route('dashboard.admin.aifut.manager-menu.items.store'),
            'title' => __('Create AIFUT Menu Item'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        AifutMenuItem::query()->create($data);

        return redirect()->route('dashboard.admin.aifut.manager-menu.index')->with([
            'type' => 'success',
            'message' => __('AIFUT menu item created successfully.'),
        ]);
    }

    public function edit(AifutMenuItem $menuItem): View
    {
        return view('panel.admin.aifut.manager-menu.items.form', [
            'item' => $menuItem,
            'menuParents' => AifutMenuItem::query()->where('id', '!=', $menuItem->id)->orderBy('code')->get(['id', 'code', 'title']),
            'method' => 'PUT',
            'action' => route('dashboard.admin.aifut.manager-menu.items.update', $menuItem),
            'title' => __('Edit AIFUT Menu Item'),
        ]);
    }

    public function update(Request $request, AifutMenuItem $menuItem): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $menuItem->update($data);

        return redirect()->route('dashboard.admin.aifut.manager-menu.index')->with([
            'type' => 'success',
            'message' => __('AIFUT menu item updated successfully.'),
        ]);
    }

    protected function validateRequest(Request $request): array
    {
        $data = $request->validate([
            'code' => 'required|string|max:191',
            'source_system' => 'required|string|max:100',
            'actor_role' => 'required|string|max:50',
            'title' => 'required|string|max:191',
            'route_name' => 'nullable|string|max:191',
            'url' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:191',
            'parent_id' => 'nullable|integer|exists:aifut_menu_items,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_visible' => 'nullable|boolean',
            'is_enabled' => 'nullable|boolean',
            'meta' => 'nullable|string',
        ]);

        $data['is_visible'] = $request->boolean('is_visible', true);
        $data['is_enabled'] = $request->boolean('is_enabled', true);
        $data['meta'] = empty($data['meta']) ? null : json_decode($data['meta'], true);

        return $data;
    }
}
