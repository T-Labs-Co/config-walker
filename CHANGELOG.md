# Changelog

All notable changes to `config-walker` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 2026-10-26

### Breaking
- **Dropped PHP 8.2/8.3 support** — requires PHP ^8.4
- **Dropped Laravel 11 support** — requires Laravel ^12|^13
- **Moved `ConfigWalkerAware` interface** — `TLabsCo\ConfigWalker\ConfigWalkerAware` → `TLabsCo\ConfigWalker\Contracts\ConfigWalkerAware`
- **Moved service provider** — `TLabsCo\ConfigWalker\ConfigWalkerServiceProvider` → `TLabsCo\ConfigWalker\Providers\ConfigWalkerServiceProvider`
- **Removed `Utils\EnumHelperTrait`** — functionality inlined into `EnumConfigWalkable`
- **Removed `ConfigWalkerCommand`** — `php artisan config-walker` no longer available
- **Removed Spatie dependency** — `spatie/laravel-package-tools` replaced with native Laravel `ServiceProvider`

### Added
- **DDD-aligned source structure**:
  - `Contracts/` directory for interfaces (`ConfigWalkerAware`)
  - `Providers/` directory for service provider
  - `Support/` directory for helper functions
- **`declare(strict_types=1)`** on all source files
- **Full type hints** on all classes, methods, and properties
- **Native service provider** — no longer depends on Spatie package tools
- **PHPStan Level 8** static analysis configuration
- **Laravel Pint** code style configuration (`pint.json`)
- **Comprehensive test suite**:
  - Unit tests for `ConfigWalker`, `WalkerParser`, `ConfigWalkable`, `EnumConfigWalkable`
  - Feature tests for service provider
  - Arch tests for strict types and interface enforcement
- **GitHub Actions workflows**:
  - `tests.yml` — PHP 8.4/8.5 × Laravel ^12/^13 matrix
  - `static-analysis.yml` — PHPStan Level 8
- **`AGENTS.md`** — package architecture and development guidelines
- **`MIGRATION.md`** — upgrade guide from v1 to v2

### Changed
- **Helper functions** moved from `src/helpers.php` to `src/Support/functions.php`
- **`EnumConfigWalkable`** — inlined `toArray()` method, removed `EnumHelperTrait` dependency
- **`ConfigWalker`** — typed properties (`array $config`, `Collection $tags`, `bool $loadWithAppConfig`, `WalkerParser $parser`), typed constructor, return types on all methods
- **`WalkerParser`** — typed parameters and private methods, `makeClassInstance` returns `object`
- **`ConfigWalkable`** — added `strict_types`, PHPStan ignore for `$this->primaryKey`
- **`ConfigWalkerAware` interface** — added return types (`toWalker(): array`, `walkerKey(): string`)
- **Facade `@method` annotations** — updated with full types, referencing `Contracts\ConfigWalkerAware`
- **PHPUnit config** — consolidated to `phpunit.xml` with Unit/Feature test suites
- **Test cases** — removed factory guesser, updated provider imports

### Removed
- `src/Commands/` directory and `ConfigWalkerCommand`
- `src/Utils/` directory and `EnumHelperTrait`
- `src/ConfigWalkerAware.php` (root level, moved to `Contracts/`)
- `src/ConfigWalkerServiceProvider.php` (root level, replaced with native provider)
- `src/helpers.php` (moved to `Support/functions.php`)
- `database/` directory (Spatie stubs)
- `resources/` directory (Spatie stubs)
- `configure.php` (Spatie scaffolding script)
- `phpstan-baseline.neon` — baseline no longer needed at Level 8
- `spatie/laravel-package-tools` dependency

### Fixed
- Exception handling in `ConfigWalker::walk()` — catches `\Throwable` instead of `\Exception`
- `getArray()` method — proper type casting for numeric keys

## [1.0.1] - 2025-06-10

### What's Changed

* Bump dependabot/fetch-metadata from 2.3.0 to 2.4.0 by @dependabot in https://github.com/T-Labs-Co/config-walker/pull/1

### New Contributors

* @dependabot made their first contribution in https://github.com/T-Labs-Co/config-walker/pull/1

**Full Changelog**: https://github.com/T-Labs-Co/config-walker/compare/v1.0.0...v1.0.1

## v1.0.0 - 2025-03-22

**Full Changelog**: https://github.com/T-Labs-Co/config-walker/commits/v1.0.0

## [Released]

## [1.0.0] - 2025-03-22

### Added

- Initial release
- Centralize different config sources into a single hub.
- Support for database tables, enums, constants, hard-coded arrays, files, and settings.
- Easy integration with Laravel models and enums.
- Customizable configuration options.
- Helper functions for accessing and managing configurations.
- Automatically load configurations with Laravel's config system.
