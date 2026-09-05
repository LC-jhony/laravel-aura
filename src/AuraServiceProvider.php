<?php

namespace Vendor\Aura;

use Illuminate\Console\AboutCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vendor\Aura\Console\InstallCommand;
use Vendor\Aura\Console\StubsPublishCommand;

class AuraServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('aura')
            ->hasCommand(InstallCommand::class)
            ->hasCommand(StubsPublishCommand::class);
    }

    public function packageBooted(): void
    {
        // NOTA: este archivo vive en package_root/src/, así que solo hace
        // falta subir UN nivel para llegar a package_root/stubs.
        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs/aura'),
        ], 'aura-stubs');

        AboutCommand::add('Aura', fn () => [
            'Versión' => '1.0.0',
            'Stacks'  => 'blade, livewire, react, vue, api (Tailwind CSS 4)',
        ]);
    }
}
