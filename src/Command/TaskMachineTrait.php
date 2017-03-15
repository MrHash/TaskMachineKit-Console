<?php

namespace ConsoleMachine\Command;

use TaskMachine\TaskMachine;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Finder\Finder;
use Workflux\Param\InputInterface as TaskInputInterface;

trait TaskMachineTrait
{
    protected $taskMachine;

    public function __construct(TaskMachine $taskMachine)
    {
        parent::__construct();

        $this->taskMachine = $taskMachine;
        $this->taskMachine->task('askConfirmation', [$this, 'askConfirmation']);
        $this->taskMachine->task('askChoice', [$this, 'askChoice']);
        $this->taskMachine->task('findFiles', [$this, 'findFiles']);
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

    public function findFiles(TaskInputInterface $taskInput)
    {
        return (new Finder)
            ->files()
            ->name($taskInput->get('name') ?? '*')
            ->ignoreUnreadableDirs($taskInput->get('ignore_unreadable_dirs') ?? true)
            ->ignoreVCS($taskInput->get('ignore_vcs') ?? true)
            ->ignoreDotFiles($taskInput->get('ignore_dot_files') ?? true)
            ->in($taskInput->get('in') ?? [])
            ->depth($taskInput->get('depth') ?? '>= 0');
    }
}
