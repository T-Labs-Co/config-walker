<?php

use TLabsCo\ConfigWalker\EnumConfigWalkable;

enum TestStatusEnum: string
{
    use EnumConfigWalkable;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
}

it('provides walkable values from enum', function () {
    $values = TestStatusEnum::walkable();
    expect($values)->toBe(['active', 'inactive', 'pending']);
});

it('provides snake-case walker key from enum', function () {
    $key = TestStatusEnum::walkerKey();
    expect($key)->toBe('test_status_enum');
});
