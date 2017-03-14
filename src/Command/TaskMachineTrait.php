<?php

namespace ConsoleMachine\Command;

use TaskMachine\TaskMachine;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Workflux\Param\InputInterface as TaskInputInterface;
use Workflux\Param\Settings;

trait TaskMachineTrait
{
    protected $taskMachine;

    public function __construct(TaskMachine $taskMachine)
    {
        $this->taskMachine = $taskMachine;

        parent::__construct();

        $this->taskMachine->task('confirm', [$this, 'confirm']);
        $this->taskMachine->task('finish', [$this, 'finish']);
        $this->taskMachine->task('fail', [$this, 'fail']);
    }

    public function confirm(InputInterface $input, OutputInterface $output, TaskInputInterface $taskInput) {
        $helper = $this->getHelper('question');
        echo ($taskInput->get('condition') ? 'input was mapped' : 'input was not mapped').PHP_EOL;
        $text = $taskInput->get('question') ?? 'Are you sure?';
        $default = $taskInput->get('default') ? '[Y\n]' : '[y\N]';
        $question = new ConfirmationQuestion($text.' '.$default.': ', false);
        return [ 'answer' => $helper->ask($input, $output, $question) ];
    }

    public function finish(TaskInputInterface $input)
    {
        echo 'finished'.PHP_EOL;
    }

    public function fail(TaskInputInterface $input)
    {
        echo 'failed'.PHP_EOL;
    }
}
