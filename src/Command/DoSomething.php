<?php

namespace ConsoleMachine\Command;

use ConsoleMachine\ConsoleMachineBuilder;
use ConsoleMachine\Handler\TestHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Workflux\Param\InputInterface as TaskInputInterface;

class DoSomething extends Command
{
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
        $machineBuilder = new ConsoleMachineBuilder;
        $machineBuilder->task('intro', function(OutputInterface $output) {
            $output->writeln('hello');
        });
        $machineBuilder->task('doAction', new TestHandler);
        $machineBuilder->task('finish', [$this, 'finish']);
        $machineBuilder->task('fail', [$this, 'fail']);

        $taskMachine = $machineBuilder->machine('something')
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
            ->finally('fail')
            ->build();

        $taskMachine->run('something');
    }
}
