# Migration Guide: v1.x → v2.0.0

This guide helps you upgrade `t-labs-co/config-walker` from v1.x to v2.0.0.

## Requirements

| Component | v1.x | v2.0.0 |
|-----------|------|--------|
| PHP | ^8.2 \| ^8.3 \| ^8.4 | **^8.4** |
| Laravel | ^11 \| ^12 | **^12 \| ^13** |

**Action:** Ensure your project runs PHP 8.4+ and Laravel 12+ before upgrading.

## Breaking Changes

### 1. PHP and Laravel Version Requirements

v2 drops support for PHP 8.2/8.3 and Laravel 11.

**Action:** Upgrade your PHP and Laravel versions before installing v2.

### 2. Namespace Changes

#### `ConfigWalkerAware` Interface Moved

- **Before:** `TLabsCo\ConfigWalker\ConfigWalkerAware`
- **After:** `TLabsCo\ConfigWalker\Contracts\ConfigWalkerAware`

**Action:** Update all imports:

```diff
- use TLabsCo\ConfigWalker\ConfigWalkerAware;
+ use TLabsCo\ConfigWalker\Contracts\ConfigWalkerAware;
```

#### Service Provider Moved

- **Before:** `TLabsCo\ConfigWalker\ConfigWalkerServiceProvider`
- **After:** `TLabsCo\ConfigWalker\Providers\ConfigWalkerServiceProvider`

**Action:** If you manually registered the provider, update the import:

```diff
- use TLabsCo\ConfigWalker\ConfigWalkerServiceProvider;
+ use TLabsCo\ConfigWalker\Providers\ConfigWalkerServiceProvider;
```

### 3. Removed Dependencies

Spatie's `laravel-package-tools` is no longer a dependency. The service provider is now a native Laravel `ServiceProvider`.

**Action:** No action needed if you use auto-discovery. If you manually registered the provider, see the namespace change above.

### 4. Removed `Utils\EnumHelperTrait`

The `EnumHelperTrait` trait has been removed. Its functionality is now inlined into `EnumConfigWalkable`.

**Action:** Remove any direct imports of `EnumHelperTrait`:

```diff
- use TLabsCo\ConfigWalker\Utils\EnumHelperTrait;
```

If you used `EnumHelperTrait` in your own code, you'll need to reimplement the `toArray()` method:

```php
// Replace this:
use EnumHelperTrait;

// With this:
public static function toArray(): array
{
    return array_map(fn ($case) => $case->value, self::cases());
}
```

### 5. Removed `ConfigWalkerCommand`

The `php artisan config-walker` command has been removed.

**Action:** If you used this command, migrate to using the `ConfigWalker` facade or `config_walker()` helper directly in your code.

### 6. Helper Functions Moved

Helper functions moved from `src/helpers.php` to `src/Support/functions.php`. The function names remain the same: `config_walker()`, `camel2dashed()`, and `class2camel()`.

**Action:** No action needed — functions are autoloaded via `composer.json`.

## Upgrade Steps

### Step 1: Update Composer

```bash
composer require t-labs-co/config-walker:^2.0
```

### Step 2: Update Imports

Search your codebase for the old namespaces and update them:

| Old Import | New Import |
|------------|------------|
| `TLabsCo\ConfigWalker\ConfigWalkerAware` | `TLabsCo\ConfigWalker\Contracts\ConfigWalkerAware` |
| `TLabsCo\ConfigWalker\ConfigWalkerServiceProvider` | `TLabsCo\ConfigWalker\Providers\ConfigWalkerServiceProvider` |
| `TLabsCo\ConfigWalker\Utils\EnumHelperTrait` | *(removed — see above)* |

### Step 3: Re-publish Config

```bash
php artisan vendor:publish --tag="config-walker-config" --force
```

### Step 4: Verify

Run your test suite and PHPStan to ensure everything works:

```bash
composer test
vendor/bin/phpstan analyse
```

## Summary of Changes

| Change | Impact |
|--------|--------|
| Drop PHP 8.2/8.3 | Users on older PHP cannot upgrade |
| Drop Laravel 11 | Users on Laravel 11 cannot upgrade |
| `ConfigWalkerAware` → `Contracts\` | Imports break |
| `Utils\EnumHelperTrait` removed | Direct imports break |
| Provider moved to `Providers\` | Manual registrations break |
| `ConfigWalkerCommand` removed | `php artisan config-walker` breaks |
| Spatie dependency removed | No action needed for auto-discovery |

## Questions?

If you encounter issues during migration, please [open an issue](https://github.com/T-Labs-Co/config-walker/issues).
