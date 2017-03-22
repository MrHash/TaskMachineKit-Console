<?php

namespace ConsoleMachine\Helper;

use Auryn\Injector;
use Symfony\Component\Console\Helper\InputAwareHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TaskMachine\Builder\TaskFactory;
use TaskMachine\Builder\YamlTaskMachineBuilder;

class TaskMachineHelper extends InputAwareHelper
{
    private $injector;

    public function __construct(Injector $injector = null)
    {
        $this->injector = $injector ?? new Injector;
    }

    public function getName()
    {
        return 'taskmachine';
    }

    public function getBuilder(OutputInterface $output)
    {
        $this->injector->share($this->getHelperSet());
        $this->injector->share($this->input)->alias(InputInterface::class, get_class($this->input));
        $this->injector->share($output)->alias(OutputInterface::class, get_class($output));

        $yamlFilePaths = [dirname(__DIR__).DIRECTORY_SEPARATOR.'consolemachine.yml'];
        return new YamlTaskMachineBuilder($yamlFilePaths, new TaskFactory($this->injector));
    }
}
