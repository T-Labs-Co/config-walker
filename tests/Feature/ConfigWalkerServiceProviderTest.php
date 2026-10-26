<?php

use TLabsCo\ConfigWalker\ConfigWalker;

it('registers config-walker in container', function () {
    expect(app('config-walker'))->toBeInstanceOf(ConfigWalker::class);
});

it('resolves the same instance', function () {
    $one = app('config-walker');
    $two = app('config-walker');
    expect($one)->toBe($two);
});

it('provides config_walker helper function', function () {
    expect(config_walker())->toBeInstanceOf(ConfigWalker::class);
});

it('helper can set and get values', function () {
    config_walker(['test_key' => 'test_value']);
    expect(config_walker('test_key'))->toBe('test_value');
});

it('merges config file', function () {
    expect(config('config-walker'))->toBeArray();
});
