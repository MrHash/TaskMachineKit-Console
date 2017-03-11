<?php

namespace ConsoleMachine\Command;

use TaskMachine\TaskMachine;

trait TaskMachineTrait
{
    protected $taskMachine;

    public function __construct(TaskMachine $taskMachine)
    {
        $this->taskMachine = $taskMachine;

        parent::__construct();
    }
}
