<?php

declare(strict_types=1);

namespace TLabsCo\ConfigWalker;

use ArrayAccess;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use TLabsCo\ConfigWalker\Exceptions\UndefinedWalkerTagException;
use TLabsCo\ConfigWalker\Exceptions\UnsupportWalkerTypeException;

/**
 * @template-implements ArrayAccess<string, mixed>
 */
final class ConfigWalker implements ArrayAccess, ConfigRepository
{
    /** @var array<string, mixed> */
    private array $config;

    /** @var Collection<string, string> */
    private Collection $tags;

    private bool $loadWithAppConfig;

    private WalkerParser $parser;

    /**
     * @param  array<string, mixed>  $options
     */
    public function __construct(array $options = [])
    {
        $this->config = (array) Arr::get($options, 'config', []);
        $this->loadWithAppConfig = (bool) Arr::get($options, 'loadWithAppConfig', config('config-walker.loadWithAppConfig', false));
        $this->tags = collect();
        $this->parser = new WalkerParser;
        $this->reset();
    }

    public function reset(): self
    {
        $this->config = [];
        $this->tags = collect();

        if ($this->loadWithAppConfig) {
            $this->loadDefault();
        }

        return $this;
    }

    public function loadDefault(): self
    {
        $this->walk('default', 'default');

        return $this;
    }

    public function makeDefault(): self
    {
        return (new self(['loadWithAppConfig' => false]))->walk('default');
    }

    /**
     * @param  mixed  $walker
     * @param  string|array<string>|null  $tags
     *
     * @throws UnsupportWalkerTypeException
     */
    public function walk($walker, ?string $key = null, $tags = null): self
    {
        try {
            $append = $this->parser->parse($walker, $key);

            $this->config = array_merge_recursive($this->config, $append);

            if ($tags && $walker !== 'default') {
                $tags = is_array($tags) ? $tags : [$tags];
                foreach ($tags as $tag) {
                    $this->addTag($tag);
                    $this->config = array_merge_recursive($this->config, [$tag => $append]);
                }
            }

        } catch (\Throwable) {
            throw new UnsupportWalkerTypeException;
        }

        return $this;
    }

    public function tag(string $tag): self
    {
        if (! $this->hasTag($tag)) {
            throw new UndefinedWalkerTagException("[ConfigWalker] Undefined tag: {$tag}");
        }

        return (new self(['loadWithAppConfig' => false]))->walk($this->get($tag, []));
    }

    /**
     * @param  array<string>  $tags
     */
    public function tags(array $tags, bool $flat = false): self
    {
        foreach ($tags as $tag) {
            if (! $this->hasTag($tag)) {
                throw new UndefinedWalkerTagException("[ConfigWalker] Undefined tag: {$tag}");
            }
        }

        $groups = new self(['loadWithAppConfig' => false]);

        foreach ($tags as $tag) {
            $groups->walk($this->get($tag, []), $flat ? null : $tag);
        }

        return $groups;
    }

    public function addTag(string $tag): self
    {
        if (! $this->hasTag($tag)) {
            $this->tags->put($tag, $tag);
        }

        return $this;
    }

    public function removeTag(string $tag, bool $includeConfig = true): self
    {
        if ($this->hasTag($tag)) {
            if ($includeConfig) {
                $this->set($tag, null);
            }
            $this->tags->pull($tag);
        }

        return $this;
    }

    public function hasTag(string $tag): bool
    {
        return $this->tags->has($tag);
    }

    /** @return array<string, string> */
    public function tagList(): array
    {
        return $this->tags->toArray();
    }

    public function has(mixed $key): bool
    {
        return Arr::has($this->config, (string) $key);
    }

    /**
     * @param  array<string>|string  $key
     */
    public function get($key, mixed $default = null): mixed
    {
        if (is_array($key)) {
            return $this->getArray($key);
        }

        return Arr::get($this->config, $key, $default);
    }

    /**
     * @param  array<string>  $keys
     * @return array<string, mixed>
     */
    public function getArray(array $keys, mixed $default = null): array
    {
        $config = [];

        foreach ($keys as $key) {
            if (is_numeric($key)) {
                [$key, $default] = [(string) $default, null];
            }

            $config[$key] = Arr::get($this->config, $key, $default);
        }

        return $config;
    }

    public function prepend(mixed $key, mixed $value): void
    {
        $array = $this->get((string) $key, []);
        array_unshift($array, $value);
        $this->set((string) $key, $array);
    }

    public function push(mixed $key, mixed $value): void
    {
        $array = $this->get((string) $key, []);
        $array[] = $value;
        $this->set((string) $key, $array);
    }

    public function pull(string $key, mixed $default = null): mixed
    {
        return Arr::pull($this->config, $key, $default);
    }

    public function forget(string $key): void
    {
        Arr::forget($this->config, $key);
    }

    /**
     * @param  array<string, mixed>|string  $key
     */
    public function set($key, mixed $value = null): void
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set($this->config, $key, $value);
        }
    }

    public function all(): array
    {
        return $this->config;
    }

    public function offsetExists(mixed $key): bool
    {
        return $this->has((string) $key);
    }

    public function offsetGet(mixed $key): mixed
    {
        return $this->get((string) $key);
    }

    public function offsetSet(mixed $key, mixed $value): void
    {
        $this->set((string) $key, $value);
    }

    public function offsetUnset(mixed $key): void
    {
        $this->forget((string) $key);
    }
}
