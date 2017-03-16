<?php

namespace ConsoleMachine\Command;

use ConsoleMachine\Builder\ConsoleMachineBuilder;
use ConsoleMachine\Handler\TestHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TaskMachine\Builder\TaskFactory;
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
        $machineBuilder = new ConsoleMachineBuilder(new TaskFactory($this->getApplication()->getInjector()));

        // define my custom tasks
        $machineBuilder->task('intro', function(OutputInterface $output) {
            $output->writeln('hello');
        });
        $machineBuilder->task('listFiles', function(TaskInputInterface $input) {
           foreach ($input->get('files') as $file) {
               echo $file->getFilename().PHP_EOL;
           }
        });
        $machineBuilder->task('finish', [$this, 'finish']);
        $machineBuilder->task('fail', [$this, 'fail']);

        // define my machine with custom and preconfigured tasks
        $taskMachine = $machineBuilder->machine('something')
            ->first('intro')->then('askChoice')
            ->task('askChoice')
                ->with(['choices' => ['a', 'b', 'c']])
                ->then('findFiles')
            ->task('findFiles')
                ->with(['name' => '*.php', 'in' => 'src/Handler'])
                ->then('listFiles')
            ->task('listFiles')->then('askConfirmation')
            ->task('askConfirmation')
                ->with(['question' => 'Make it so?'])
                ->when([
                    'output.response' => 'finish',
                    '!output.response' => 'fail'
                ])
            ->finally('finish')
            ->finally('fail')
            ->build();

        $output = $taskMachine->run('something');
    }
}
