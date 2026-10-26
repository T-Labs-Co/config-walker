# config-walker

A Laravel package that centralizes configuration from multiple sources into a single, tag-scoped hub.

## Architecture

`ConfigWalker` is the main class that holds a merged config array and a tag registry. It implements `ArrayAccess` and Laravel's `ConfigRepository` interface, so it can be used like a normal config container.

### Core Components

| Component | Location | Role |
|-----------|----------|------|
| `ConfigWalker` | `src/ConfigWalker.php` | Main class: merges, stores, and retrieves config |
| `WalkerParser` | `src/WalkerParser.php` | Parses walker sources into arrays |
| `ConfigWalkable` | `src/ConfigWalkable.php` | Trait for Eloquent models |
| `EnumConfigWalkable` | `src/EnumConfigWalkable.php` | Trait for backed enums |
| `ConfigWalkerAware` | `src/Contracts/ConfigWalkerAware.php` | Interface for custom sources |
| `ConfigWalkerServiceProvider` | `src/Providers/ConfigWalkerServiceProvider.php` | Native Laravel service provider |
| `ConfigWalker` Facade | `src/Facades/ConfigWalker.php` | Facade alias |
| Helper functions | `src/Support/functions.php` | `config_walker()`, `camel2dashed()`, `class2camel()` |

### Walker Source Types

1. **`'default'` string** — loads Laravel's `config()` array
2. **Array** — merged directly
3. **`ConfigWalkable` trait** — calls `walkable()` on the model instance
4. **`EnumConfigWalkable` trait** — extracts enum values
5. **`ConfigWalkerAware` interface** — calls `toWalker()` on the object
6. **Plain string** — wrapped in an array `[$string]`

### Tag System

- `walk($walker, $key, $tags)` — attaches config under one or more tags
- `tag($tag)` — returns a new `ConfigWalker` scoped to a single tag
- `tags($tags, $flat)` — merges multiple tags into a new `ConfigWalker`
- Tags are tracked in an internal `Collection`

### DDD Structure

```
src/
├── Contracts/
│   └── ConfigWalkerAware.php
├── Exceptions/
│   ├── UndefinedWalkerTagException.php
│   └── UnsupportWalkerTypeException.php
├── Facades/
│   └── ConfigWalker.php
├── Providers/
│   └── ConfigWalkerServiceProvider.php
├── Support/
│   └── functions.php
├── ConfigWalker.php
├── ConfigWalkable.php
├── EnumConfigWalkable.php
└── WalkerParser.php
```

## Coding Standards

- `declare(strict_types=1)` on all `src/` files
- Full type hints on properties, parameters, and return types
- PHPStan Level 8 — no baseline
- Laravel Pint with `laravel` preset

## Testing

- **Unit tests** — `tests/Unit/`: core API, tags, parser, traits, enums
- **Feature tests** — `tests/Feature/`: service provider, container, helpers
- **Arch tests** — `tests/ArchTest.php`: strict types, interface contracts, no debug functions
- Run: `composer test`

## Dev Workflow

1. Make changes in `src/`
2. Run `composer format` to apply Pint
3. Run `composer analyse` for PHPStan
4. Run `composer test` for the full suite
5. Commit with descriptive messages
