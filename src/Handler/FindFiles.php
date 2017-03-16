<?php

namespace ConsoleMachine\Handler;

use Symfony\Component\Finder\Finder;
use Workflux\Param\InputInterface;

class FindFiles extends ConsoleTaskHandler
{
    public function execute(InputInterface $input): array
    {
        $files = (new Finder)
            ->files()
            ->name($input->get('name') ?? '*')
            ->ignoreUnreadableDirs($input->get('ignore_unreadable_dirs') ?? true)
            ->ignoreVCS($input->get('ignore_vcs') ?? true)
            ->ignoreDotFiles($input->get('ignore_dot_files') ?? true)
            ->in($input->get('in') ?? '.')
            ->depth($input->get('depth') ?? '>= 0');

        return ['files' => $files];
    }
}
