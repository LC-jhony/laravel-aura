<?php

namespace Laravel\Aura\Console\Concerns;

use Illuminate\Filesystem\Filesystem;

trait InstallsApiStack
{
    protected function installApiStack(): void
    {
        $filesystem = new Filesystem;
        $stubs = $this->stubsPath('api');

        $filesystem->ensureDirectoryExists(app_path('Http/Controllers/Auth'));

        $filesystem->copyDirectory($stubs.'/app', app_path());
        $filesystem->copyDirectory($stubs.'/routes', base_path('routes'));

        $this->copyTests($stubs, 'api');

        $this->requireComposerPackages(['laravel/sanctum:^4.0']);

        $this->registerApiRoutes();

        $this->runMigrations();
    }

    protected function registerApiRoutes(): void
    {
        $appFile = base_path('bootstrap/app.php');

        if (! file_exists($appFile)) {
            $this->components->warn('No se encontró bootstrap/app.php — registra routes/api.php manualmente en withRouting().');

            return;
        }

        $contents = file_get_contents($appFile);

        if (str_contains($contents, "api: __DIR__.'/../routes/api.php'")) {
            return;
        }

        $needle = "web: __DIR__.'/../routes/web.php',";

        if (str_contains($contents, $needle)) {
            $contents = str_replace(
                $needle,
                $needle."\n        api: __DIR__.'/../routes/api.php',",
                $contents
            );
            file_put_contents($appFile, $contents);
        } else {
            $this->components->warn("No se pudo registrar routes/api.php automáticamente — añade api: __DIR__.'/../routes/api.php' a withRouting() en bootstrap/app.php.");
        }
    }
}
