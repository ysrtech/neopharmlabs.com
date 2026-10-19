<?php

$freeCrop = ['default' => [['name' => 'default', 'ratio' => 0]]];

return [
    'admin_app_title' => 'Neopharm Labs CMS',

    // The CMS lives at /npoffice (change with ADMIN_APP_PATH in .env).
    'admin_app_path' => ltrim(env('ADMIN_APP_PATH', 'npoffice'), '/'),

    'block_editor' => [
        // Only our own blocks (resources/views/twill/blocks) are offered.
        'use_twill_blocks' => [],
        'crops' => [
            'image' => $freeCrop,
            'images' => $freeCrop,
        ],
    ],

    'enabled' => [
        'settings' => true,
    ],
];
