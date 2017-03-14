<?php

namespace ConsoleMachine\Command;

use TaskMachine\Handler\TaskHandlerInterface;
use Workflux\Param\InputInterface;

class TestHandler implements TaskHandlerInterface
{
    public function execute(InputInterface $input): array
    {
        echo 'my test handler'.PHP_EOL;
        return [ 'result' => (bool)random_int(0, 1) ];
    }
}
