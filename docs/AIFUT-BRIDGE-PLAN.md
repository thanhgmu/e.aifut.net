# AIFUT Bridge Plan for e.aifut.net

## 1. Decision
Build the **AIFUT module/bridge first**, then implement **Manager Menu** as a capability inside that bridge.

## 2. Core principles
The module must satisfy these conditions:

1. **Upgrade-safe**: custom code must stay outside vendor code and avoid deep edits to MagicAI core.
2. **Tenancy-ready**: e.aifut.net can operate as a shared runtime while AIFUT-core remains the control plane/source of truth.
3. **User autonomy**: users can back up/export data and configuration from e.aifut.net.
4. **Natural-language orchestration**: AIFUT-core can accept commands in natural language and drive workflows in e.aifut.net and other connected systems.

## 3. Architecture stance

### 3.1 Role of each system
- **e.aifut.net / MagicAI**: early operating lane, service delivery, workflow validation, revenue capture, user-facing runtime.
- **AIFUT bridge module**: adapter layer inside e.aifut.net for menu policy, tenancy context, storage/domain policy mirroring, backup/export hooks, and future API sync.
- **AIFUT-core**: source of truth for tenant, workspace, package, quota, governance, orchestration, affiliate/provider catalog, and commercial rules.

### 3.2 Source of truth policy
For long-term cleanliness:
- **AIFUT-core decides** package, quota, storage policy, domain policy, tenant status, provider catalog, affiliate IDs.
- **e.aifut.net displays and executes** operational actions, mirrors status, and can submit change requests back to AIFUT-core.

## 4. Recommendation for storage/domain controls

### 4.1 Should users see storage/domain controls on e.aifut.net?
**Yes, but not as the final authority.**

Recommended behavior:
- On **AIFUT-core**: full configuration, package eligibility, billing, provider/affiliate settings, policy enforcement.
- On **e.aifut.net**: show current plan/status/usage; allow the user to request change or start a guided action; sync confirmed state from AIFUT-core.

This gives good UX without creating two conflicting control planes.

## 5. Supported storage modes
1. **Shared AIFUT storage**: shared application/runtime, quota billed by AIFUT-core.
2. **Third-party provider**: selectable catalog curated by AIFUT-core, with affiliate-aware provider IDs.
3. **Existing provider/account**: user connects infrastructure they already own.
4. **Local storage**: user-managed local/on-prem option where supported.

## 6. Supported domain modes
1. **AIFUT-provided domain/subdomain**
2. **Affiliate purchase via integrated provider catalog**
3. **Existing domain already owned by the user**
4. **Local domain / local network mode**

## 7. Manager Menu scope
Manager Menu should not be a simple drag-drop menu utility. It should become a **policy-driven menu orchestration layer**.

It should be able to resolve visibility/order by:
- actor: superadmin / admin / user
- scope: global / tenant / workspace / user
- source: MagicAI-native / AIFUT-core-managed
- plan/package
- enabled features
- storage mode
- domain mode
- backup/export permissions

## 8. Minimum V1 build order

### V1 — foundation
- create isolated AIFUT config and route entrypoint
- expose bridge manifest/policy endpoints
- document source-of-truth rules
- reserve namespace for future services/controllers

### V2 — Manager Menu
- menu registry
- sort order and parent/child nesting
- show/hide by role
- show/hide by plan/feature/storage/domain mode
- admin UI for menu policy

### V3 — AIFUT-core integration
- tenant resolver
- plan/quota sync
- storage/domain provider catalog sync
- change-request flow from e.aifut.net to AIFUT-core
- audit trail

### V4 — orchestration and backup
- backup/export jobs on e.aifut.net
- inbound command execution contracts from AIFUT-core
- workflow actions spanning e.aifut.net and external systems

## 9. Suggested data model for Manager Menu
Recommended tables (names may change):
- `aifut_menu_items`
- `aifut_menu_rules`
- `aifut_menu_assignments`
- `aifut_tenant_bindings`
- `aifut_provider_catalog_cache`
- `aifut_change_requests`
- `aifut_backup_profiles`

Key rule dimensions:
- actor_role
- scope_type / scope_id
- plan_code
- feature_code
- storage_mode
- domain_mode
- source_system
- sort_order
- parent_item_id
- is_visible
- is_enabled

## 10. What was implemented now
This repo now includes:
- `config/aifut.php` for bridge policy defaults
- `routes/custom_routes_web.php` as the custom route entrypoint
- `app/Http/Controllers/Aifut/AifutBridgeController.php` for initial manifest/policy endpoints

## 11. Next recommended implementation step
Build **database schema + admin UI spec for Manager Menu V1** under the AIFUT bridge, not directly against MagicAI core menus.
