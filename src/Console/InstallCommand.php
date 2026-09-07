<?php

namespace Vendor\Aura\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Vendor\Aura\Console\Concerns\CopiesTests;
use Vendor\Aura\Console\Concerns\InstallsApiStack;
use Vendor\Aura\Console\Concerns\InstallsBladeStack;
use Vendor\Aura\Console\Concerns\InstallsInertiaStacks;
use Vendor\Aura\Console\Concerns\InstallsLivewireStack;

use function Laravel\Prompts\callout;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\form;
use function Laravel\Prompts\spin;

/**
 * Clon fiel de la arquitectura de laravel/breeze 2.x:
 * un único InstallCommand que delega en un trait por stack.
 *
 * @see https://github.com/laravel/breeze/blob/2.x/src/Console/InstallCommand.php
 */
class InstallCommand extends Command
{
    use CopiesTests;
    use InstallsApiStack;
    use InstallsBladeStack;
    use InstallsInertiaStacks;
    use InstallsLivewireStack;

    protected $signature = 'aura:install
                            {stack? : El stack a instalar (blade,livewire,react,vue,api)}
                            {--dark : Incluir soporte de modo oscuro}
                            {--pest : Forzar stubs de tests con Pest}
                            {--ssr : Instalar soporte de Inertia SSR (react/vue)}
                            {--typescript : Usar TypeScript en el stack Inertia (react/vue)}
                            {--composer=global : Ruta absoluta al binario de Composer}
                            {--force : Sobrescribir archivos existentes sin confirmación}';

    protected $description = 'Instalar la estructura de autenticación Aura (blade, livewire, react, vue o api) con Tailwind CSS 4';

    public function handle(): int
    {
        // Si se pasa el stack por CLI, usar flujo directo
        if ($this->argument('stack')) {
            return $this->installFromCli();
        }

        // Wizard interactivo con Laravel Prompts
        return $this->installFromWizard();
    }

    // ------------------------------------------------------------------
    // CLI directo (con flags)
    // ------------------------------------------------------------------

    protected function installFromCli(): int
    {
        $stack = $this->argument('stack');

        if (! in_array($stack, ['blade', 'livewire', 'react', 'vue', 'api'])) {
            $this->components->error("Stack desconocido [{$stack}]. Disponibles: blade, livewire, react, vue, api.");

            return self::FAILURE;
        }

        if (! $this->option('force') && ! confirm("Aura publicará rutas, vistas/páginas y tests del stack [{$stack}] en tu aplicación. ¿Continuar?", default: true)) {
            return self::FAILURE;
        }

        return $this->installStack($stack, $this->getOptionsFromFlags());
    }

    protected function getOptionsFromFlags(): array
    {
        $options = [];

        if ($this->option('dark')) {
            $options[] = 'dark';
        }

        if ($this->option('pest')) {
            $options[] = 'pest';
        }

        if ($this->option('ssr')) {
            $options[] = 'ssr';
        }

        if ($this->option('typescript')) {
            $options[] = 'typescript';
        }

        return $options;
    }

    // ------------------------------------------------------------------
    // Wizard interactivo (Laravel Prompts form)
    // ------------------------------------------------------------------

    protected function installFromWizard(): int
    {
        $responses = form()
            ->select(
                label: '¿Qué stack de Aura quieres instalar?',
                options: [
                    'blade' => 'Blade (controladores tradicionales)',
                    'livewire' => 'Livewire (componentes de página, sin Volt)',
                    'react' => 'React (Inertia)',
                    'vue' => 'Vue (Inertia)',
                    'api' => 'Solo API (Sanctum, sin frontend)',
                ],
                name: 'stack'
            )
            ->multiselect(
                label: 'Opciones adicionales',
                options: [
                    'dark' => 'Modo oscuro',
                    'pest' => 'Tests con Pest',
                    'ssr' => 'Soporte Inertia SSR (react/vue)',
                    'typescript' => 'TypeScript (react/vue)',
                ],
                name: 'options',
                required: false,
                hint: 'Opcional. Presiona Espacio para seleccionar, Enter para continuar.'
            )
            ->confirm(
                label: '¿Confirmar instalación?',
                name: 'confirm'
            )
            ->submit();

        if (! $responses['confirm']) {
            $this->components->warn('Instalación cancelada.');

            return self::FAILURE;
        }

        // Filtrar opciones no válidas para el stack seleccionado
        $options = $this->filterOptionsForStack(
            $responses['stack'],
            $responses['options']
        );

        return $this->installStack($responses['stack'], $options);
    }

    protected function filterOptionsForStack(string $stack, array $options): array
    {
        // SSR y TypeScript solo aplican para react/vue
        if (! in_array($stack, ['react', 'vue'])) {
            $options = array_diff($options, ['ssr', 'typescript']);
        }

        return array_values($options);
    }

    // ------------------------------------------------------------------
    // Instalación principal
    // ------------------------------------------------------------------

    protected function installStack(string $stack, array $options): int
    {
        $this->components->info("Instalando Aura — stack [{$stack}]...");

        // 1. Stubs comunes
        spin(
            message: 'Copiando stubs comunes...',
            callback: fn () => $this->installCommonStubs()
        );

        // 2. Delega en el trait correspondiente al stack elegido.
        $method = match ($stack) {
            'blade' => 'installBladeStack',
            'livewire' => 'installLivewireStack',
            'react' => 'installReactStack',
            'vue' => 'installVueStack',
            'api' => 'installApiStack',
            default => throw new \InvalidArgumentException("Stack desconocido: {$stack}"),
        };

        $this->{$method}();

        // 3. Resumen final
        callout(
            label: '¡Aura instalado correctamente!',
            content: $this->getSuccessMessage($stack),
            type: 'success'
        );

        return self::SUCCESS;
    }

    protected function getSuccessMessage(string $stack): string
    {
        $commands = ['php artisan serve'];

        if ($stack !== 'api') {
            $commands[] = 'npm run dev';
        }

        return 'Ejecuta: '.implode(' y ', $commands);
    }

    // ------------------------------------------------------------------
    // Stubs comunes
    // ------------------------------------------------------------------

    protected function installCommonStubs(): void
    {
        $filesystem = new Filesystem;
        $common = $this->stubsPath('common');

        $filesystem->ensureDirectoryExists(app_path('Models'));
        $filesystem->ensureDirectoryExists(resource_path('css'));

        $filesystem->copyDirectory($common.'/app', app_path());
        $filesystem->copyDirectory($common.'/resources', resource_path());
    }

    /**
     * Resuelve la ruta a un grupo de stubs. Si el usuario publicó y
     * personalizó los suyos (php artisan aura:publish-stubs), se
     * usan esos en vez de los del paquete.
     */
    protected function stubsPath(string $group): string
    {
        $custom = base_path("stubs/aura/{$group}");

        return is_dir($custom)
            ? $custom
            : realpath(__DIR__."/../../stubs/{$group}");
    }

    protected function usesPest(): bool
    {
        if ($this->option('pest')) {
            return true;
        }

        $composer = base_path('composer.json');

        return str_contains((string) file_get_contents($composer), 'pestphp/pest');
    }

    // ------------------------------------------------------------------
    // Tailwind CSS v4 (CSS-first) + Vite — usado por todos los stacks
    // con frontend (blade, livewire, react, vue).
    // ------------------------------------------------------------------

    protected function installTailwindV4(array $viteInputs, array $extraDevDeps = []): void
    {
        $this->removeNodePackages(['autoprefixer', 'postcss', 'tailwindcss']);

        $this->updateNodePackages(function (array $packages) use ($extraDevDeps): array {
            return [
                '@tailwindcss/vite' => '^4.0',
                'tailwindcss' => '^4.0',
            ] + $extraDevDeps + $packages;
        });

        foreach (['tailwind.config.js', 'tailwind.config.ts', 'postcss.config.js', 'postcss.config.cjs'] as $file) {
            if (file_exists(base_path($file))) {
                unlink(base_path($file));
            }
        }

        $inputsList = collect($viteInputs)->map(fn ($i) => "'{$i}'")->implode(', ');

        $config = <<<JS
            import { defineConfig } from 'vite';
            import laravel from 'laravel-vite-plugin';
            import tailwindcss from '@tailwindcss/vite';

            export default defineConfig({
                plugins: [
                    laravel({
                        input: [{$inputsList}],
                        refresh: true,
                    }),
                    tailwindcss(),
                ],
            });
            JS;

        file_put_contents(base_path('vite.config.js'), $config);
    }

    // ------------------------------------------------------------------
    // Paquetes Node
    // ------------------------------------------------------------------

    protected function updateNodePackages(callable $callback, bool $dev = true): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $key = $dev ? 'devDependencies' : 'dependencies';
        $packages = json_decode(file_get_contents(base_path('package.json')), true);
        $packages[$key] = $callback($packages[$key] ?? []);
        ksort($packages[$key]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    protected function removeNodePackages(array $packages): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configuration = json_decode(file_get_contents(base_path('package.json')), true);

        foreach ($packages as $package) {
            unset($configuration['devDependencies'][$package]);
            unset($configuration['dependencies'][$package]);
        }

        file_put_contents(
            base_path('package.json'),
            json_encode($configuration, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    protected function runNpmInstall(): void
    {
        if (! $this->option('force') && ! confirm('¿Ejecutar "npm install && npm run build" ahora?', default: true)) {
            return;
        }

        $this->runProcess([$this->npmBinary(), 'install']);
        $this->runProcess([$this->npmBinary(), 'run', 'build']);
    }

    protected function npmBinary(): string
    {
        return windows_os() ? 'npm.cmd' : 'npm';
    }

    // ------------------------------------------------------------------
    // Paquetes Composer
    // ------------------------------------------------------------------

    protected function requireComposerPackages(array $packages): void
    {
        $command = array_merge(
            [$this->phpBinary(), $this->composerBinary(), 'require'],
            $packages
        );

        $this->runProcess($command, ['COMPOSER_MEMORY_LIMIT' => '-1']);
    }

    protected function phpBinary(): string
    {
        return (new PhpExecutableFinder)->find(false) ?: 'php';
    }

    protected function composerBinary(): string
    {
        $composer = $this->option('composer');

        return $composer === 'global' ? 'composer' : '"'.$this->phpBinary().'" "'.$composer.'"';
    }

    protected function runProcess(array $command, array $env = []): void
    {
        $process = new Process($command, base_path(), $env);
        $process->setTimeout(null);
        $process->run(function ($type, $output) {
            $this->output->write($output);
        });
    }

    // ------------------------------------------------------------------
    // Migraciones
    // ------------------------------------------------------------------

    protected function runMigrations(): void
    {
        if ($this->option('force') || confirm('¿Ejecutar las migraciones de base de datos ahora?', default: true)) {
            $this->call('migrate');
        }
    }
}
