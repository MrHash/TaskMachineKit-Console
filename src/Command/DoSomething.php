<?php

namespace ConsoleMachine\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;

class DoSomething extends Command
{
    use TaskMachineTrait;

    protected function configure()
    {
        $this->setName('console:do');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->taskMachine->task('intro', function(OutputInterface $output) {
            $output->writeln('hello');
        });
        $this->taskMachine->task('action', new TestHandler);

        $this->taskMachine->machine('something')
            ->first('intro')->then('action')
            ->task('action')
                ->map(['result' => 'condition'])
                ->then('confirm')
            ->task('confirm')
                ->with(['question' => 'Make it so?'])
                ->when([
                    'output.get("answer")' => 'finish', //@todo add __get to paramholdertrait
                    '!output.get("answer")' => 'fail'
                ])
            ->finally('finish')
            ->finally('fail');

        $this->taskMachine->run('something');
    }
}
