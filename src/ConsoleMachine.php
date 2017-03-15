<?php

namespace ConsoleMachine;

use Auryn\Injector;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleMachine extends Application
{
    private $injector;

    public function __construct(array $commands = [], Injector $injector = null)
    {
        parent::__construct('Console Machine', 'v0.1');

        $this->injector = $injector ?? new Injector;

        foreach ($commands as $command) {
            if (!$command instanceof Command) {
                $command = $this->injector->make($command);
            }
            $this->add($command);
        }
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->injector->share($input)->alias(InputInterface::class, get_class($input));
        $this->injector->share($output)->alias(OutputInterface::class, get_class($output));

        return parent::doRun($input, $output);
    }
}
