<?php

namespace TLabsCo\ConfigWalker;

use ArrayAccess;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Arr;
use TLabsCo\ConfigWalker\Exceptions\UndefinedWalkerTagException;
use TLabsCo\ConfigWalker\Exceptions\UnsupportWalkerTypeException;

final class ConfigWalker implements ArrayAccess, ConfigRepository
{
    /**
     * @var array|ArrayAccess|mixed
     */
    private $config;

    /**
     * Group some config under tag
     *
     * @var \Illuminate\Support\Collection|array|mixed
     */
    private $tags;

    /**
     * this will merge with laravel config as default
     *
     * @var bool
     */
    private $loadWithAppConfig;

    /**
     * @var WalkerParser
     */
    private $parser;

    /**
     * ConfigWalker constructor.
     *
     * @param  array  $options  = [
     *                          'config' => [],
     *                          'loadWithAppConfig' => false
     *                          ]
     */
    public function __construct($options = [])
    {
        $this->config = Arr::get($options, 'config', []);
        $this->loadWithAppConfig = Arr::get($options, 'loadWithAppConfig', config('config-walker.loadWithAppConfig', false));
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

    public function makeDefault(): ConfigWalker
    {
        return (new ConfigWalker(['loadWithAppConfig' => false]))->walk('default');
    }

    /**
     * @param  mixed  $walker
     * @param  string|null  $key
     * @param  string|array|null  $tags
     * @return $this
     *
     * @throws UnsupportWalkerTypeException
     */
    public function walk($walker, $key = null, $tags = null): self
    {
        try {
            $append = $this->parser->parse($walker, $key);

            $this->config = array_merge_recursive($this->config, $append);

            // Ignore tag default
            if ($tags && $walker !== 'default') {
                $tags = is_array($tags) ? $tags : [$tags];
                foreach ($tags as $tag) {
                    $this->addTag($tag);
                    $this->config = array_merge_recursive($this->config, [$tag => $append]);
                }
            }

        } catch (\Exception $e) {
            throw new UnsupportWalkerTypeException;
        }

        return $this;
    }

    public function tag($tag): ConfigWalker
    {
        // if no tags then return it self
        if (! $this->hasTag($tag)) {
            throw new UndefinedWalkerTagException("[ConfigWalker] Undefined tag: {$tag}");
        }

        return (new ConfigWalker(['loadWithAppConfig' => false]))->walk($this->get($tag, []));
    }

    public function tags($tags, $flat = false): ConfigWalker
    {
        // check tags does existing
        foreach ($tags as $tag) {
            if (! $this->hasTag($tag)) {
                throw new UndefinedWalkerTagException("[ConfigWalker] Undefined tag: {$tag}");
            }
        }

        $groups = (new ConfigWalker(['loadWithAppConfig' => false]));

        foreach ($tags as $tag) {
            if (! $this->hasTag($tag)) {
                throw new UndefinedWalkerTagException("[ConfigWalker] Undefined tag: {$tag}");
            }

            $groups->walk($this->get($tag, []), $flat ? null : $tag);
        }

        return $groups;
    }

    public function addTag($tag): self
    {
        if (! $this->hasTag($tag)) {
            $this->tags->put($tag, $tag);
        }

        return $this;
    }

    public function removeTag($tag, $includeConfig = true): self
    {
        if ($this->hasTag($tag)) {
            if ($includeConfig) {
                $this->set($tag, null);
            }
            $this->tags->pull($tag);
        }

        return $this;
    }

    public function hasTag($tag): bool
    {
        return $this->tags->has($tag);
    }

    public function tagList(): array
    {
        return $this->tags->toArray();
    }

    /**
     * @param  string  $key
     */
    public function has($key): bool
    {
        return Arr::has($this->config, $key);
    }

    /**
     * @param  array|string  $key
     * @param  mixed  $default
     */
    public function get($key, $default = null): mixed
    {
        if (is_array($key)) {
            return $this->getArray($key);
        }

        return Arr::get($this->config, $key, $default);
    }

    /**
     * @param  array  $keys
     */
    public function getArray($keys, $default = null): array
    {
        $config = [];

        foreach ($keys as $key) {

            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }

            $config[$key] = Arr::get($this->config, $key, $default);
        }

        return $config;
    }

    /**
     * @param  array|string  $key
     * @return void
     */
    public function prepend($key, $value)
    {
        $array = $this->get($key, []);

        array_unshift($array, $value);

        $this->set($key, $array);
    }

    /**
     * @param  array|string  $key
     * @return void
     */
    public function push($key, $value)
    {
        $array = $this->get($key, []);

        $array[] = $value;

        $this->set($key, $array);
    }

    /**
     * @param  string  $key
     */
    public function pull($key, $default = null): mixed
    {
        return Arr::pull($this->config, $key, $default);
    }

    /**
     * @param  string  $key
     */
    public function forget($key): void
    {
        Arr::forget($this->config, $key);
    }

    /**
     * @param  array|string  $key
     * @param  mixed  $value
     */
    public function set($key, $value = null): void
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

    /**
     * @param  string  $key
     */
    public function offsetExists($key): bool
    {
        return $this->has($key);
    }

    /**
     * @param  string  $key
     */
    public function offsetGet($key): mixed
    {
        return $this->get($key);
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     */
    public function offsetSet($key, $value): void
    {
        $this->set($key, $value);
    }

    /**
     * @param  string  $key
     */
    public function offsetUnset($key): void
    {
        $this->set($key, null);
    }
}
