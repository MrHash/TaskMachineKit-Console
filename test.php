<?php

require __DIR__.'/vendor/autoload.php';

use Auryn\Injector;
use ConsoleMachine\Command\DoSomething;
use ConsoleMachine\ConsoleMachine;
use Symfony\Component\Console\Application;
use TaskMachine\TaskMachine;

// $tm = new TaskMachine;

// $tm->task('start', function () {
//     echo 'start';
// });

// $tm->task('finish', function () {
//     echo 'finish';
// });


// $commands = [
//     DoSomething::class
// ];

// $injector = new Injector;
// $injector->make(ConsoleMachine::class, [':commands' => $commands, ':taskMachine' => $tm])->run();


(new ConsoleMachine([
    DoSomething::class
]))->run();