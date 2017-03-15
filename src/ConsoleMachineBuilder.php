<?php

namespace ConsoleMachine;

use TaskMachine\Builder\TaskMachineBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Workflux\Param\InputInterface as TaskInputInterface;

class ConsoleMachineBuilder extends TaskMachineBuilder
{
    public function __construct(FactoryInterface $factory = null)
    {
        parent::__construct($factory);

        $this->task('askConfirmation', [$this, 'askConfirmation']);
        $this->task('askChoice', [$this, 'askChoice']);
    }

    public function askConfirmation(InputInterface $input, OutputInterface $output, TaskInputInterface $taskInput)
    {
        $helper = $this->getHelper('question');
        $message = $taskInput->get('question') ?? 'Are you sure?';
        $default = $taskInput->get('default') ? '[Y\n]' : '[y\N]';
        $question = new ConfirmationQuestion('<question>'.$message.'</> <comment>'.$default.'</>: ', false);
        return ['response' => $helper->ask($input, $output, $question)];
    }

    public function askChoice(InputInterface $input, OutputInterface $output, TaskInputInterface $taskInput)
    {
        $availableChoices = $taskInput->get('choices');

        if (empty($availableChoices)) {
            throw new \InvalidArgumentException('Choices must not be empty');
        }

        $helper = $this->getHelper('question');
        $message = $taskInput->get('question') ?? 'Choose from the following options:';

        $question = new ChoiceQuestion('<question>'.$message.'</>', $availableChoices);
        $question->setMultiselect($taskInput->get('multiselect'));

        return ['reponse' => $helper->ask($input, $output, $question)];
    }

}
