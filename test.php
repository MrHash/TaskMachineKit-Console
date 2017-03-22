<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use TaskMachineKit\Console\Command\DoSomething;
use TaskMachineKit\Console\Helper\TaskMachineHelper;

$app = new Application;
$helperSet = $app->getHelperSet();
$helperSet->set(new TaskMachineHelper);
$app->setHelperSet($helperSet);

$app->addCommands([
    new DoSomething
]);

$app->run();
