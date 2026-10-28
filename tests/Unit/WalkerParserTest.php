<?php

use TLabsCo\ConfigWalker\ConfigWalkable;
use TLabsCo\ConfigWalker\Contracts\ConfigWalkerAware;
use TLabsCo\ConfigWalker\WalkerParser;

it('parses default string to app config', function () {
    config()->set('app.name', 'TestApp');
    $parser = new WalkerParser;
    $result = $parser->parse('default');

    expect($result)->toBeArray();
    expect($result['app']['name'])->toBe('TestApp');
});

it('parses array walker directly', function () {
    $parser = new WalkerParser;
    $result = $parser->parse(['key' => 'value']);

    expect($result)->toBe(['key' => 'value']);
});

it('parses array walker with key', function () {
    $parser = new WalkerParser;
    $result = $parser->parse(['key' => 'value'], 'mykey');

    expect($result)->toBe(['mykey' => ['key' => 'value']]);
});

it('parses walkable aware object', function () {
    $parser = new WalkerParser;

    $aware = new class implements ConfigWalkerAware
    {
        public function toWalker(): array
        {
            return ['status' => 'active'];
        }

        public function walkerKey(): string
        {
            return 'my_aware';
        }
    };

    $result = $parser->parse($aware);
    expect($result)->toBe(['my_aware' => ['status' => 'active']]);
});

it('parses walkable trait object with snake-case key fallback', function () {
    $parser = new WalkerParser;

    $walkable = new class
    {
        use ConfigWalkable;

        protected $primaryKey = 'name';

        public static function query()
        {
            return collect([['name' => 'item1'], ['name' => 'item2']]);
        }
    };

    $result = $parser->parse($walkable);
    $key = $walkable->walkerKey();
    expect($result)->toHaveKey($key);
    expect($result[$key])->toBe(['item1', 'item2']);
});

it('parses string walker without key', function () {
    $parser = new WalkerParser;
    $result = $parser->parse('just_a_string');

    expect($result)->toBe(['just_a_string']);
});

it('parses string walker with key', function () {
    $parser = new WalkerParser;
    $result = $parser->parse('just_a_string', 'mykey');

    expect($result)->toBe(['mykey' => ['just_a_string']]);
});
