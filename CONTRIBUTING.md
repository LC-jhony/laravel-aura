# Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/my-feature`)
3. Make your changes
4. Run checks before committing:
   ```bash
   vendor/bin/pint --test
   composer analyse
   composer test
   ```
5. Commit your changes (`git commit -m 'feat: add my feature'`)
6. Push to the branch (`git push origin feature/my-feature`)
7. Open a Pull Request

## Code Style

- Use Pint with `laravel` preset
- 4-space indentation for PHP
- 2-space indentation for JSON/YAML
- No debug statements in `src/` (arch tests enforce this)

## Testing

- Write Pest tests for new features
- Include architecture tests when adding new patterns
- Ensure CI passes: `vendor/bin/pint --test && composer analyse && composer test`

## Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` new feature
- `fix:` bug fix
- `docs:` documentation changes
- `refactor:` code refactoring
- `test:` adding tests
- `chore:` maintenance tasks
