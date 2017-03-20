<?php

namespace ConsoleMachine\Command;

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
        $machineBuilder = $this->getApplication()->getMachineBuilder();

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
        $taskMachine = $machineBuilder
            ->machine('something')
            ->intro([
                'initial' => true,
                'transition' => 'askChoice'
            ])
            ->askChoice([
                'input' => ['choices' => ['src/Builder', 'src/Command', 'src/Handler']],
                'map' => ['output.response' => 'in'],
                'transition' => 'findFiles'
            ])
            ->findFiles([
                'input' => ['name' => '*.php'],
                'transition' => 'listFiles'
            ])
            ->listFiles(['transition' => 'askConfirmation'])
            ->askConfirmation([
                'input' => ['question' => 'Is that correct?'],
                'transition' => [
                    'output.response' => 'finish',
                    '!output.response' => 'fail'
                ]
            ])
            ->finish(['final' => true])
            ->fail(['final' => true])
            ->build();

        $output = $taskMachine->run('something');
    }
}
