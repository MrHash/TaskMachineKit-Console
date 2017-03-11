<?php

namespace ConsoleMachine\Command;

use Symfony\Component\Console\Command\Command;
use TaskMachine\TaskMachine;

abstract class TaskMachineCommand extends Command
{
    protected $taskMachine;

    public function __construct(TaskMachine $taskMachine)
    {
        $this->taskMachine = $taskMachine;

        parent::__construct();
    }
}
