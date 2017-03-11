<?php

namespace ConsoleMachine\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Workflux\Param\InputInterface as TaskInputInterface;

class DoSomething extends Command
{
    use TaskMachineTrait;

    protected function configure()
    {
        $this->setName('console:do');
    }

    public function confirm(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Are you sure? [y\N]: ', false);
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->taskMachine->task('intro', function(OutputInterface $output) {
            $output->writeln('hello');
        });
        $this->taskMachine->task('action', new TestHandler);
        $this->taskMachine->task('confirm', [$this, 'confirm']);
        $this->taskMachine->task('finish', [$this, 'finish']);
        $this->taskMachine->task('fail', [$this, 'fail']);

        $this->taskMachine->machine('something')
            ->first('intro')->then('action')
            ->task('action')->then('confirm')
            ->task('confirm')
                ->when('output.get("answer")', 'finish')
                ->when('!output.get("answer")', 'fail')
            ->finally('finish')
            ->finally('fail');

        $this->taskMachine->run('something');
    }
}
