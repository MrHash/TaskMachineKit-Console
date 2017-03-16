<?php

require __DIR__.'/vendor/autoload.php';

use ConsoleMachine\Command\DoSomething;
use ConsoleMachine\ConsoleMachine;

$app = new ConsoleMachine;

$app->addCommands([
    DoSomething::class
]);

$app->run();