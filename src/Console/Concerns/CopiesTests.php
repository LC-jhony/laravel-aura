<?php

namespace Laravel\Aura\Console\Concerns;

use Illuminate\Filesystem\Filesystem;

trait CopiesTests
{
    protected function copyTests(string $stubsPath, string $stack): void
    {
        if (! $this->usesPest()) {
            $this->components->warn('Stubs de PHPUnit aún no incluidos — Pest es el predeterminado desde Laravel 11.');

            return;
        }

        $filesystem = new Filesystem;
        $source = $stubsPath.'/tests/Feature/Auth';

        if (! is_dir($source)) {
            return;
        }

        $filesystem->ensureDirectoryExists(base_path('tests/Feature/Auth'));
        $filesystem->copyDirectory($source, base_path('tests/Feature/Auth'));
    }
}
