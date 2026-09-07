# Changelog

All notable changes to `laravel-aura` will be documented in this file.

## [1.0.0] - 2026-09-07

### Added

- Initial release
- Auth scaffolding for Laravel 13
- 5 stacks: blade, livewire, react, vue, api
- Tailwind CSS 4 support
- Dark mode option (`--dark`)
- Pest support (`--pest`)
- Inertia SSR support (`--ssr`)
- TypeScript support (`--typescript`)
- Custom stubs publishing (`aura:publish-stubs`)
- Override stubs via `stubs/aura/{group}/` in app
- `--force` flag to skip confirmations
- Arch tests enforcing no debug statements in src/
- CI with PHP 8.3/8.4 × Laravel 13 matrix
