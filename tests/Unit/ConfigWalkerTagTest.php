<?php

use TLabsCo\ConfigWalker\ConfigWalker;
use TLabsCo\ConfigWalker\Exceptions\UndefinedWalkerTagException;

it('can walk with a tag', function () {
    $walker = new ConfigWalker;
    $walker->walk(['key' => 'value'], null, 'tag1');

    expect($walker->hasTag('tag1'))->toBeTrue();
    expect($walker->get('tag1.key'))->toBe('value');
});

it('can retrieve tag-scoped config', function () {
    $walker = new ConfigWalker;
    $walker->walk(['key' => 'value'], null, 'tag1');

    $tagged = $walker->tag('tag1');
    expect($tagged->get('key'))->toBe('value');
});

it('can list tags', function () {
    $walker = new ConfigWalker;
    $walker->walk(['a' => 1], null, 'tag1');
    $walker->walk(['b' => 2], null, 'tag2');

    expect($walker->tagList())->toContain('tag1', 'tag2');
});

it('can add and remove tags', function () {
    $walker = new ConfigWalker;
    $walker->addTag('mytag');
    expect($walker->hasTag('mytag'))->toBeTrue();

    $walker->removeTag('mytag');
    expect($walker->hasTag('mytag'))->toBeFalse();
});

it('throws exception for undefined tag', function () {
    $walker = new ConfigWalker;

    $walker->tag('nonexistent');
})->throws(UndefinedWalkerTagException::class);

it('throws exception for undefined tag in tags method', function () {
    $walker = new ConfigWalker;

    $walker->tags(['nonexistent']);
})->throws(UndefinedWalkerTagException::class);

it('can merge multiple tags', function () {
    $walker = new ConfigWalker;
    $walker->walk(['a' => 1], null, 'tag1');
    $walker->walk(['b' => 2], null, 'tag2');

    $merged = $walker->tags(['tag1', 'tag2']);
    expect($merged->get('tag1.a'))->toBe(1);
    expect($merged->get('tag2.b'))->toBe(2);
});

it('can merge tags flat', function () {
    $walker = new ConfigWalker;
    $walker->walk(['a' => 1], null, 'tag1');
    $walker->walk(['b' => 2], null, 'tag2');

    $merged = $walker->tags(['tag1', 'tag2'], flat: true);
    expect($merged->get('a'))->toBe(1);
    expect($merged->get('b'))->toBe(2);
});

it('can walk with multiple tags at once', function () {
    $walker = new ConfigWalker;
    $walker->walk(['key' => 'value'], null, ['tag1', 'tag2']);

    expect($walker->hasTag('tag1'))->toBeTrue();
    expect($walker->hasTag('tag2'))->toBeTrue();
});

it('remove tag can exclude config', function () {
    $walker = new ConfigWalker;
    $walker->walk(['key' => 'value'], null, 'tag1');
    $walker->removeTag('tag1', includeConfig: false);

    expect($walker->hasTag('tag1'))->toBeFalse();
    expect($walker->get('tag1.key'))->toBe('value');
});
