<?php

namespace TLabsCo\ConfigWalker\Commands;

use Illuminate\Console\Command;

class ConfigWalkerCommand extends Command
{
    public $signature = 'config-walker';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
