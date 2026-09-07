<?php

use Illuminate\Contracts\Console\Kernel;

it('registers the aura:install command', function () {
    expect(app(Kernel::class)->all())
        ->toHaveKey('aura:install');
});

it('rejects an unknown stack', function () {
    $this->artisan('aura:install', ['stack' => 'unknown-stack', '--force' => true])
        ->assertExitCode(1);
});
