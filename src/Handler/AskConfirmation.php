<?php

namespace TaskMachineKit\Console\Handler;

use Symfony\Component\Console\Question\ConfirmationQuestion;
use Workflux\Param\InputInterface;

class AskConfirmation extends ConsoleTaskHandler
{
    public function execute(InputInterface $input): array
    {
        $helper = $this->helperSet->get('question');
        $message = $input->get('question') ?? 'Are you sure?';
        $default = $input->get('default') ? '[Y\n]' : '[y\N]';
        $question = new ConfirmationQuestion('<question>'.$message.'</> <comment>'.$default.'</>: ', false);
        return ['response' => $helper->ask($this->input, $this->output, $question)];
    }
}
