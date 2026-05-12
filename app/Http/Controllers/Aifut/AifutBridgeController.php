<?php

declare(strict_types=1);

namespace App\Http\Controllers\Aifut;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AifutBridgeController extends Controller
{
    public function manifest(): JsonResponse
    {
        return response()->json([
            'module' => 'AIFUT Bridge',
            'enabled' => (bool) config('aifut.enabled'),
            'bridge_version' => (string) config('aifut.bridge_version'),
            'principles' => [
                'upgrade_safe' => true,
                'core_as_source_of_truth' => true,
                'tenant_ready' => true,
                'natural_language_orchestration_ready' => true,
            ],
            'source_of_truth' => config('aifut.core.source_of_truth', []),
            'ui' => config('aifut.ui', []),
            'storage_modes' => config('aifut.storage_modes', []),
            'domain_modes' => config('aifut.domain_modes', []),
            'manager_menu' => config('aifut.manager_menu', []),
        ]);
    }

    public function policy(): JsonResponse
    {
        return response()->json([
            'storage' => [
                'authoritative_panel' => 'AIFUT-core',
                'eapp_behavior' => 'mirror-status-and-submit-change-request',
            ],
            'domain' => [
                'authoritative_panel' => 'AIFUT-core',
                'eapp_behavior' => 'mirror-status-and-submit-change-request',
            ],
            'backup' => [
                'authoritative_panel' => 'e.aifut.net',
                'note' => 'Users should be able to export backup/data/configuration from the operational app directly.',
            ],
            'natural_language' => [
                'entrypoint' => 'AIFUT-core conversation window',
                'execution_target' => 'e.aifut.net and connected systems through AIFUT-core orchestration',
            ],
        ]);
    }
}
