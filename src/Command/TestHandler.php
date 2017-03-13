<?php

namespace ConsoleMachine\Command;

use TaskMachine\Handler\TaskHandlerInterface;
use Workflux\Param\InputInterface;
use Workflux\Param\Settings;

class TestHandler implements TaskHandlerInterface
{
    public function execute(InputInterface $inpu, Settings $settings): array
    {
        echo 'my test handler'.PHP_EOL;
        return [ 'result' => (bool)random_int(0, 1) ];
    }
}
