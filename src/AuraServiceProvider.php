<?php

namespace Laravel\Aura;

use Illuminate\Console\AboutCommand;
use Laravel\Aura\Console\InstallCommand;
use Laravel\Aura\Console\StubsPublishCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
