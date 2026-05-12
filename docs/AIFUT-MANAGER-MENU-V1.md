# AIFUT Manager Menu V1

## Goal
Build Manager Menu as a **policy-driven menu layer** inside the AIFUT bridge, not as a direct patch on MagicAI core menus.

## V1 scope
- menu registry
- parent/child nesting
- manual sort order
- show/hide by role
- show/hide by plan / feature / storage mode / domain mode
- tenant-aware rule resolution
- change-request logging for actions that must be approved/synced by AIFUT-core

## Tables added
- `aifut_menu_items`
- `aifut_menu_rules`
- `aifut_tenant_bindings`
- `aifut_change_requests`

## Resolution order
1. identify current actor role
2. identify tenant/workspace binding
3. load candidate menu items
4. apply matching rules by scope specificity:
   - user
   - workspace
   - tenant
   - global
5. apply plan/feature/storage/domain filters
6. resolve final visibility, enabled state, and sort order

## Safe architecture rules
- do not edit vendor packages
- keep custom routes/controllers/config under dedicated AIFUT namespace
- use bridge tables for menu policy instead of mutating original MagicAI menu definitions where possible
- keep AIFUT-core as source of truth for package/quota/storage/domain policy

## UX policy
- e.aifut.net may show current storage/domain state and let the user request changes
- final approval/provisioning policy remains in AIFUT-core
- backup/export actions should stay available directly in e.aifut.net

## Implemented progress
- menu resolver service skeleton added
- admin preview screen added
- menu item create/edit flow added
- rule create/edit flow added
- sample seeder added: `database/seeders/AifutManagerMenuSeeder.php`

## Next coding step
- add safer validation for JSON/rule conflicts
- add initial mirrored menu map from live MagicAI menu structure
- add tenant binding UI and storage/domain request screens
