<?php

return [
    'enabled' => env('AIFUT_ENABLED', true),

    'bridge_version' => '0.1.0',

    'core' => [
        'base_url' => env('AIFUT_CORE_URL'),
        'timeout_seconds' => (int) env('AIFUT_CORE_TIMEOUT', 10),
        'source_of_truth' => [
            'tenant' => 'aifut-core',
            'workspace' => 'aifut-core',
            'plan' => 'aifut-core',
            'quota' => 'aifut-core',
            'storage_policy' => 'aifut-core',
            'domain_policy' => 'aifut-core',
            'affiliate_provider_catalog' => 'aifut-core',
        ],
    ],

    'ui' => [
        'allow_status_mirror_on_eapp' => true,
        'allow_change_request_from_eapp' => true,
        'allow_direct_policy_mutation_on_eapp' => false,
    ],

    'storage_modes' => [
        'shared-aifut-storage',
        'third-party-provider',
        'existing-provider-account',
        'local-storage',
    ],

    'domain_modes' => [
        'aifut-provided',
        'affiliate-purchase',
        'existing-domain',
        'local-domain',
    ],

    'manager_menu' => [
        'enabled' => true,
        'scopes' => ['global', 'tenant', 'workspace', 'user'],
        'actors' => ['superadmin', 'admin', 'user'],
        'dimensions' => [
            'plan',
            'feature',
            'storage_mode',
            'domain_mode',
            'source',
        ],
    ],

    'natural_language_orchestration' => [
        'enabled' => true,
        'mode' => 'command-to-workflow-via-aifut-core',
    ],
];
