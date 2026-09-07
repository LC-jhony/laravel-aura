# Aura

Scaffolding de autenticación para **Laravel 13** — clon fiel de la arquitectura real de
[laravel/breeze](https://github.com/laravel/breeze/tree/2.x/src/Console) (`InstallCommand` + traits
por stack), con **Tailwind CSS 4** en todos los stacks con frontend.

## Stacks disponibles

| Stack | Descripción |
|---|---|
| `blade` | Blade + controladores tradicionales |
| `livewire` | **Livewire puro (sin Volt)** — componentes de clase de página completa |
| `react` | Inertia.js + React 19 |
| `vue` | Inertia.js + Vue 3 |
| `api` | Solo API (Sanctum), sin frontend |

## Instalación

```bash
composer require laravel/aura --dev
php artisan aura:install blade
# o: php artisan aura:install livewire --pest
# o: php artisan aura:install react --ssr --typescript
# o: php artisan aura:install vue
# o: php artisan aura:install api
```

Si omites el stack, el comando te lo pregunta de forma interactiva (usa
[Laravel Prompts](https://laravel.com/docs/prompts), igual que Breeze real).

## Calidad del paquete

Este paquete sigue la estructura real de
[spatie/package-skeleton-laravel](https://github.com/spatie/package-skeleton-laravel) y las prácticas de
["Building your own Laravel Packages" (Laravel News)](https://laravel-news.com/building-your-own-laravel-packages):

- Service Provider basado en [spatie/laravel-package-tools](https://github.com/spatie/laravel-package-tools)
- Tests con [Pest](https://pestphp.com/) + [Pest Plugin Arch](https://pestphp.com/docs/arch-testing) + [Orchestra Testbench](https://github.com/orchestral/testbench)
- Estilo de código con [Laravel Pint](https://laravel-news.com/laravel-pint) (`pint.json`)
- Análisis estático con [Larastan](https://github.com/larastan/larastan) (`phpstan.neon`)
- `.gitattributes` con `export-ignore` para que los releases no incluyan archivos de desarrollo
- CI en GitHub Actions (`.github/workflows/tests.yml`), matriz PHP 8.3/8.4 × Laravel 13
- [Testbench Workbench](https://packages.tools/testbench/getting-started/skeleton.html) para desarrollo local
  con una app Laravel real embebida en el propio paquete

```bash
composer install
vendor/bin/testbench workbench:install   # genera workbench/ (app Laravel de prueba)
composer test        # Pest (incluye arch tests)
composer format       # Pint
composer analyse       # Larastan
vendor/bin/testbench serve   # levanta la app de workbench/ para probar Aura en vivo
```

## Comandos

- `aura:install {stack?} {--dark} {--pest} {--ssr} {--typescript} {--force}`
- `aura:publish-stubs {--force}` — personaliza los stubs en `stubs/aura` antes de instalar.
