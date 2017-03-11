<?php

namespace ConsoleMachine\Command;

use TaskMachine\Handler\HandlerInterface;
use Workflux\Param\InputInterface;

class TestHandler implements HandlerInterface
{
    public function execute(InputInterface $input): array
    {
        echo 'my test handler'.PHP_EOL;
        return [];
    }
}
