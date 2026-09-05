<?php

namespace Vendor\Aura\Console\Concerns;

use Illuminate\Filesystem\Filesystem;

trait InstallsLivewireStack
{
    /**
     * Stack Livewire SIN Volt: componentes de clase "full page"
     * (App\Livewire\Auth\Login extends Livewire\Component), igual
     * que el stack "livewire" clásico de Breeze antes de que existiera Volt.
     */
    protected function installLivewireStack(): void
    {
        $filesystem = new Filesystem;
        $stubs = $this->stubsPath('livewire');

        $filesystem->ensureDirectoryExists(app_path('Livewire/Auth'));
        $filesystem->ensureDirectoryExists(app_path('Livewire/Profile'));
        $filesystem->ensureDirectoryExists(resource_path('views/livewire/auth'));
        $filesystem->ensureDirectoryExists(resource_path('views/livewire/profile'));
        $filesystem->ensureDirectoryExists(resource_path('views/layouts'));

        $filesystem->copyDirectory($stubs.'/app', app_path());
        $filesystem->copyDirectory($stubs.'/resources', resource_path());
        $filesystem->copyDirectory($stubs.'/routes', base_path('routes'));

        $this->copyTests($stubs, 'livewire');

        $this->requireComposerPackages(['livewire/livewire:^3.6']);

        $this->installTailwindV4(
            viteInputs: ['resources/css/app.css', 'resources/js/app.js'],
            extraDevDeps: ['alpinejs' => '^3.14'],
        );

        $this->runNpmInstall();
        $this->runMigrations();
    }
}
