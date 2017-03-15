<?php

namespace ConsoleMachine\Command;

use ConsoleMachine\Handler\TestHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Workflux\Param\InputInterface as TaskInputInterface;

class DoSomething extends Command
{
    use TaskMachineTrait;

    protected function configure()
    {
        $this->setName('console:do');
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
        $this->taskMachine->task('doAction', new TestHandler);
        $this->taskMachine->task('finish', [$this, 'finish']);
        $this->taskMachine->task('fail', [$this, 'fail']);

        $this->taskMachine->machine('something')
            ->first('intro')->then('askChoice')
            ->task('askChoice')
                ->with([
                    'choices' => ['a', 'b', 'c']
                ])
                ->then('doAction')
            ->task('doAction')
                ->map(['result' => 'condition'])
                ->then('askConfirmation')
            ->task('askConfirmation')
                ->with(['question' => 'Make it so?'])
                ->when([
                    'output.response' => 'finish',
                    '!output.response' => 'fail'
                ])
            ->finally('finish')
            ->finally('fail');

        $this->taskMachine->run('something');
    }
}
