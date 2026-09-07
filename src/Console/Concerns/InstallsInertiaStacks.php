<?php

namespace Vendor\Aura\Console\Concerns;

use Illuminate\Filesystem\Filesystem;

trait InstallsInertiaStacks
{
    protected function installReactStack(): void
    {
        $this->installInertiaStack('react');
    }

    protected function installVueStack(): void
    {
        $this->installInertiaStack('vue');
    }

    /**
     * React y Vue comparten el 100% del backend (controladores, rutas,
     * middleware HandleInertiaRequests) — solo difiere resources/js.
     * Igual patrón que usa breeze real (InstallsInertiaStacks).
     */
    protected function installInertiaStack(string $frontend): void
    {
        $filesystem = new Filesystem;
        $common = $this->stubsPath('inertia-common');
        $frontendStubs = $this->stubsPath($frontend);

        $filesystem->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        $filesystem->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        $filesystem->ensureDirectoryExists(app_path('Http/Middleware'));
        $filesystem->ensureDirectoryExists(resource_path('js/Pages/Auth'));

        // Backend compartido
        $filesystem->copyDirectory($common.'/app', app_path());
        $filesystem->copyDirectory($common.'/routes', base_path('routes'));
        $this->copyTests($common, $frontend);

        // Frontend específico
        $filesystem->copyDirectory($frontendStubs.'/resources', resource_path());

        $this->registerInertiaMiddleware();

        $isTypescript = $this->option('typescript') && $frontend === 'react';
        $ext = $frontend === 'vue' ? 'vue' : ($isTypescript ? 'tsx' : 'jsx');

        $this->installTailwindV4(
            viteInputs: ['resources/css/app.css', "resources/js/app.{$ext}"],
            extraDevDeps: $this->inertiaNodeDependencies($frontend, $isTypescript),
        );

        if ($this->option('ssr')) {
            $this->components->info("Recuerda: para SSR necesitas además compilar resources/js/ssr.{$ext} y ejecutar 'php artisan inertia:start-ssr'.");
        }

        $this->runNpmInstall();
        $this->runMigrations();
    }

    protected function inertiaNodeDependencies(string $frontend, bool $isTypescript): array
    {
        $common = [
            '@inertiajs/react' => null, // se sobreescribe abajo según frontend
        ];

        if ($frontend === 'react') {
            $deps = [
                '@inertiajs/react' => '^2.0',
                'react' => '^19.0',
                'react-dom' => '^19.0',
                '@vitejs/plugin-react' => '^4.3',
            ];

            if ($isTypescript) {
                $deps['typescript'] = '^5.6';
                $deps['@types/react'] = '^19.0';
                $deps['@types/react-dom'] = '^19.0';
            }

            return $deps;
        }

        return [
            '@inertiajs/vue3' => '^2.0',
            'vue' => '^3.5',
            '@vitejs/plugin-vue' => '^5.1',
        ];
    }

    protected function registerInertiaMiddleware(): void
    {
        $kernelFile = base_path('bootstrap/app.php');

        if (! file_exists($kernelFile)) {
            $this->components->warn('No se encontró bootstrap/app.php — registra HandleInertiaRequests manualmente en el grupo de middleware "web".');

            return;
        }

        $contents = file_get_contents($kernelFile);

        if (str_contains($contents, 'HandleInertiaRequests::class')) {
            return;
        }

        $needle = '->withMiddleware(function (Middleware $middleware) {';
        $replacement = $needle."\n        "."\$middleware->web(append: [\n            \\App\\Http\\Middleware\\HandleInertiaRequests::class,\n        ]);\n";

        if (str_contains($contents, $needle)) {
            file_put_contents($kernelFile, str_replace($needle, $replacement, $contents));
        } else {
            $this->components->warn('No se pudo registrar HandleInertiaRequests automáticamente — añádelo tú al grupo de middleware "web" en bootstrap/app.php.');
        }
    }
}
