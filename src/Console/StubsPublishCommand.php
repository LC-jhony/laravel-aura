<?php

namespace Vendor\Aura\Console;

use Illuminate\Console\Command;

class StubsPublishCommand extends Command
{
    protected $signature = 'aura:publish-stubs {--force : Sobrescribir stubs existentes}';

    protected $description = 'Publicar stubs de Aura para personalizarlos antes de instalar';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'aura-stubs',
            '--force' => $this->option('force'),
        ]);

        $this->components->info('Stubs publicados en [stubs/aura].');
        $this->components->info('Edítalos y vuelve a ejecutar [php artisan aura:install] para aplicarlos.');

        return self::SUCCESS;
    }
}
