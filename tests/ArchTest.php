<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('it enforces strict types')
    ->expect('TLabsCo\ConfigWalker')
    ->toUseStrictTypes();

arch('all contracts are interfaces')
    ->expect('TLabsCo\ConfigWalker\Contracts')
    ->toBeInterfaces();
