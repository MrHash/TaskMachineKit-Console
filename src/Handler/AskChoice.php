<?php

namespace ConsoleMachine\Handler;

use Symfony\Component\Console\Question\ChoiceQuestion;
use Workflux\Param\InputInterface;

class AskChoice extends ConsoleTaskHandler
{
    public function execute(InputInterface $input): array
    {
        $availableChoices = $input->get('choices');

        if (empty($availableChoices)) {
            throw new \InvalidArgumentException('Choices must not be empty');
        }

        $helper = $this->helperSet->get('question');
        $message = $input->get('question') ?? 'Choose from the following options:';

        $question = new ChoiceQuestion('<question>'.$message.'</>', $availableChoices);
        $question->setMultiselect($input->get('multiselect'));

        return ['response' => $helper->ask($this->input, $this->output, $question)];
    }
}
