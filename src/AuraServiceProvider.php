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
            ->hasConfigFile()
            ->hasCommand(InstallCommand::class)
            ->hasCommand(StubsPublishCommand::class);
    }

    public function register(): void
    {
        parent::register();

        $this->app->bind(Aura::class, fn () => new Aura);
    }

    public function packageBooted(): void
    {
        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs/aura'),
        ], 'aura-stubs');

        if (class_exists(AboutCommand::class)) {
            AboutCommand::add('Aura', fn () => [
                'Versión' => '1.0.0',
                'Stacks' => 'blade, livewire, react, vue, api (Tailwind CSS 4)',
            ]);
        }
    }
}
