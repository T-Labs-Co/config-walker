<?php

use TLabsCo\ConfigWalker\ConfigWalker;

uses()->in('TLabsCo\ConfigWalker\Tests\Unit');

beforeEach(function () {
    config()->set('config-walker.loadWithAppConfig', false);
});

it('can walk an array and retrieve values', function () {
    $walker = new ConfigWalker;
    $walker->walk(['app' => ['name' => 'TestApp', 'debug' => true]]);

    expect($walker->get('app.name'))->toBe('TestApp');
    expect($walker->get('app.debug'))->toBeTrue();
});

it('can merge multiple walks', function () {
    $walker = new ConfigWalker;
    $walker->walk(['app' => ['name' => 'TestApp']]);
    $walker->walk(['app' => ['debug' => true]]);

    expect($walker->get('app.name'))->toBe('TestApp');
    expect($walker->get('app.debug'))->toBeTrue();
});

it('can get with default value', function () {
    $walker = new ConfigWalker;
    expect($walker->get('missing.key', 'fallback'))->toBe('fallback');
});

it('can set and get values', function () {
    $walker = new ConfigWalker;
    $walker->set('key1', 'value1');
    expect($walker->get('key1'))->toBe('value1');
});

it('can set multiple values at once', function () {
    $walker = new ConfigWalker;
    $walker->set(['key1' => 'value1', 'key2' => 'value2']);
    expect($walker->get('key1'))->toBe('value1');
    expect($walker->get('key2'))->toBe('value2');
});

it('can forget a key', function () {
    $walker = new ConfigWalker;
    $walker->set('key1', 'value1');
    $walker->forget('key1');
    expect($walker->has('key1'))->toBeFalse();
});

it('can push to an array value', function () {
    $walker = new ConfigWalker;
    $walker->set('items', ['a']);
    $walker->push('items', 'b');
    expect($walker->get('items'))->toBe(['a', 'b']);
});

it('can prepend to an array value', function () {
    $walker = new ConfigWalker;
    $walker->set('items', ['b']);
    $walker->prepend('items', 'a');
    expect($walker->get('items'))->toBe(['a', 'b']);
});

it('can pull a value', function () {
    $walker = new ConfigWalker;
    $walker->set('key1', 'value1');
    $pulled = $walker->pull('key1');
    expect($pulled)->toBe('value1');
    expect($walker->has('key1'))->toBeFalse();
});

it('can check if a key exists', function () {
    $walker = new ConfigWalker;
    $walker->set('key1', 'value1');
    expect($walker->has('key1'))->toBeTrue();
    expect($walker->has('missing'))->toBeFalse();
});

it('can get all config', function () {
    $walker = new ConfigWalker;
    $walker->walk(['a' => 1, 'b' => 2]);
    expect($walker->all())->toHaveKeys(['a', 'b']);
});

it('can reset', function () {
    $walker = new ConfigWalker;
    $walker->walk(['a' => 1]);
    $walker->reset();
    expect($walker->all())->toBe([]);
});

it('supports array access', function () {
    $walker = new ConfigWalker;
    $walker->walk(['key1' => 'value1']);

    expect(isset($walker['key1']))->toBeTrue();
    expect($walker['key1'])->toBe('value1');

    $walker['key2'] = 'value2';
    expect($walker->get('key2'))->toBe('value2');

    unset($walker['key1']);
    expect($walker->has('key1'))->toBeFalse();
});

it('can get array of keys', function () {
    $walker = new ConfigWalker;
    $walker->walk(['a' => 1, 'b' => 2, 'c' => 3]);
    expect($walker->getArray(['a', 'b']))->toBe(['a' => 1, 'b' => 2]);
});

it('can walk with a key', function () {
    $walker = new ConfigWalker;
    $walker->walk(['name' => 'Test'], 'myconfig');
    expect($walker->get('myconfig.name'))->toBe('Test');
});
