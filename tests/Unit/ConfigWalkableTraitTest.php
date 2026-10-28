<?php

use TLabsCo\ConfigWalker\ConfigWalkable;

it('provides snake-case walker key', function () {
    $obj = new class
    {
        use ConfigWalkable;

        protected $primaryKey = 'name';

        public static function query()
        {
            return collect([['name' => 'item1']]);
        }
    };

    $key = $obj->walkerKey();
    expect($key)->toBeString();
    expect($key)->not->toBeEmpty();
});

it('provides walkable values', function () {
    $obj = new class
    {
        use ConfigWalkable;

        protected $primaryKey = 'name';

        public static function query()
        {
            return collect([['name' => 'item1'], ['name' => 'item2']]);
        }
    };

    $values = $obj->walkable();
    expect($values)->toBeArray();
    expect($values)->toContain('item1', 'item2');
});
