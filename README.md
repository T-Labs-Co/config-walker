# config-walker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/t-labs-co/config-walker.svg?style=flat-square)](https://packagist.org/packages/t-labs-co/config-walker)
[![Tests](https://img.shields.io/github/actions/workflow/status/t-labs-co/config-walker/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/t-labs-co/config-walker/actions/workflows/tests.yml)
[![Static Analysis](https://img.shields.io/github/actions/workflow/status/t-labs-co/config-walker/static-analysis.yml?branch=main&label=phpstan&style=flat-square)](https://github.com/t-labs-co/config-walker/actions/workflows/static-analysis.yml)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%208-blue.svg?style=flat-square)](https://phpstan.org/)
[![Total Downloads](https://img.shields.io/packagist/dt/t-labs-co/config-walker.svg?style=flat-square)](https://packagist.org/packages/t-labs-co/config-walker)

A centralized configuration walker for Laravel — merge multiple config sources, tag-based scoping, and enum-aware config resolution.

Centralize data from **database tables, enums, constants, arrays, and settings** into a single organized hub. Avoid copy-paste and keep your config clean.

## Features

- Centralize different config sources into a single hub
- Support for database tables, enums, constants, hard-coded arrays, and settings
- Tag-based config scoping and merging
- Easy integration with Laravel models and enums via traits
- Helper function `config_walker()` for quick access
- Auto-load with Laravel's config system

## Requirements

| Package  | Version     |
|----------|-------------|
| PHP      | ^8.4        |
| Laravel  | ^12 \| ^13  |

## Walkable Sources

| Source Type | Trait / Interface | Description |
|-------------|-------------------|-------------|
| Eloquent Model | `ConfigWalkable` | Export model data via `walkable()` |
| Backed Enum | `EnumConfigWalkable` | Auto-export enum values |
| Custom Object | `Contracts\ConfigWalkerAware` | Implement `toWalker()` and `walkerKey()` |
| Array | — | Pass arrays directly to `walk()` |
| Laravel Config | — | Use `'default'` walker to load app config |

## Installation

```bash
composer require t-labs-co/config-walker
```

> **Upgrading from v1.x?** Read the [Migration Guide](https://github.com/T-Labs-Co/config-walker/blob/main/docs/MIGRATION_TO_V2.md) for a step-by-step upgrade path.

Publish the config file:

```bash
php artisan vendor:publish --tag="config-walker-config"
```

## Usage

### With a Model

Use the `ConfigWalkable` trait on your model:

```php
use TLabsCo\ConfigWalker\ConfigWalkable;

class Category extends Model
{
    use HasFactory, ConfigWalkable;

    public function walkable(): array
    {
        return self::query()
            ->pluck('name', 'slug')
            ->toArray();
    }

    public function walkerKey(): string
    {
        return 'categories';
    }
}
```

Then walk the model:

```php
use TLabsCo\ConfigWalker\Facades\ConfigWalker;

ConfigWalker::walk(Category::class);
ConfigWalker::get('categories');
```

### With an Enum

Use the `EnumConfigWalkable` trait:

```php
use TLabsCo\ConfigWalker\EnumConfigWalkable;

enum CategoryType: string
{
    use EnumConfigWalkable;

    case Post = 'post';
    case Product = 'product';
    case Book = 'book';
}
```

```php
ConfigWalker::walk(CategoryType::class);
ConfigWalker::get('category_type'); // key is auto-generated from enum name
```

### With ConfigWalkerAware

Implement the `Contracts\ConfigWalkerAware` interface:

```php
use TLabsCo\ConfigWalker\Contracts\ConfigWalkerAware;

class MyConfigSource implements ConfigWalkerAware
{
    public function toWalker(): array
    {
        return ['key' => 'value'];
    }

    public function walkerKey(): string
    {
        return 'my_source';
    }
}
```

### Tags

Group configs under tags for scoped retrieval:

```php
ConfigWalker::walk(['a' => 1], null, 'group1');
ConfigWalker::walk(['b' => 2], null, 'group2');

// Get a single tag's config
ConfigWalker::tag('group1')->get('a');

// Merge multiple tags
ConfigWalker::tags(['group1', 'group2']);

// Flat merge
ConfigWalker::tags(['group1', 'group2'], flat: true);
```

### Helper Function

```php
// Get the ConfigWalker instance
config_walker();

// Get a config value
config_walker('your-key');

// Set config values
config_walker(['your-key' => 'your-value']);
```

### Access Laravel Config

```php
// Load Laravel's app config into ConfigWalker
ConfigWalker::loadDefault();

// Or set in config/config-walker.php:
// 'loadWithAppConfig' => true

// Access via ConfigWalker
ConfigWalker::makeDefault()->get('app.name');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Upgrading from v1?

See the [Migration Guide](MIGRATION.md) for step-by-step upgrade instructions.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ty Huynh](https://github.com/ty-huynh)
- [All Contributors](../../contributors)

## Quality

- **PHPStan Level 8** — strict static analysis
- **Pest PHP** — comprehensive test suite
- **Laravel Pint** — code style enforcement

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
