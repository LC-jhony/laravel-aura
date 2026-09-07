<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Stack
    |--------------------------------------------------------------------------
    |
    | The default stack to install when no argument is provided to
    | the aura:install command.
    |
    */

    'default' => env('AURA_STACK', 'blade'),

    /*
    |--------------------------------------------------------------------------
    | Available Stacks
    |--------------------------------------------------------------------------
    |
    | The stacks available for installation. Each stack provides a different
    | frontend/backend combination for authentication scaffolding.
    |
    */

    'stacks' => [
        'blade' => [
            'description' => 'Blade with traditional controllers',
            'has_frontend' => true,
        ],
        'livewire' => [
            'description' => 'Livewire full-page components (no Volt)',
            'has_frontend' => true,
        ],
        'react' => [
            'description' => 'Inertia.js + React 19',
            'has_frontend' => true,
        ],
        'vue' => [
            'description' => 'Inertia.js + Vue 3',
            'has_frontend' => true,
        ],
        'api' => [
            'description' => 'API only (Sanctum)',
            'has_frontend' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tailwind CSS Version
    |--------------------------------------------------------------------------
    |
    | The Tailwind CSS version to use. Aura uses Tailwind CSS 4 by default.
    |
    */

    'tailwind_version' => env('AURA_TAILWIND_VERSION', '4'),

];
