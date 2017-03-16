<?php

namespace ConsoleMachine\Builder;

use ConsoleMachine\Handler\AskChoice;
use ConsoleMachine\Handler\AskConfirmation;
use ConsoleMachine\Handler\FindFiles;
use TaskMachine\Builder\TaskMachineBuilder;
use Workflux\Builder\FactoryInterface;

class ConsoleMachineBuilder extends TaskMachineBuilder
{
    public function __construct(FactoryInterface $factory = null)
    {
        parent::__construct($factory);

        $this->task('askConfirmation', AskConfirmation::class);
        $this->task('askChoice', AskChoice::class);
        $this->task('findFiles', FindFiles::class);
    }
}
