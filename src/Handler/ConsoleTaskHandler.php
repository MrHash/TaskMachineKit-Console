<?php

namespace TaskMachineKit\Console\Handler;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TaskMachine\Handler\TaskHandlerInterface;

abstract class ConsoleTaskHandler implements TaskHandlerInterface
{
    protected $input;

    protected $output;

    protected $helperSet;

    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        HelperSet $helperSet
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->helperSet = $helperSet;
    }
}