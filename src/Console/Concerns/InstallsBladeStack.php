<?php

namespace Vendor\Aura\Console\Concerns;

use Illuminate\Filesystem\Filesystem;

trait InstallsBladeStack
{
    protected function installBladeStack(): void
    {
        $filesystem = new Filesystem;
        $stubs = $this->stubsPath('blade');

        $filesystem->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        $filesystem->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        $filesystem->ensureDirectoryExists(resource_path('views/auth'));
        $filesystem->ensureDirectoryExists(resource_path('views/layouts'));

        $filesystem->copyDirectory($stubs.'/app', app_path());
        $filesystem->copyDirectory($stubs.'/resources', resource_path());
        $filesystem->copyDirectory($stubs.'/routes', base_path('routes'));

        $this->copyTests($stubs, 'blade');

        $this->installTailwindV4(
            viteInputs: ['resources/css/app.css', 'resources/js/app.js'],
        );

        $this->runNpmInstall();
        $this->runMigrations();
    }
}
