# TaskMachineKit - Console
### TaskMachine orchestration support kit for Symfony Console applications.

See https://github.com/MrHash/TaskMachine for details about TaskMachine and how it works.

## Integration
You can integrate TaskMachine into you Symfony Console application easily as follows:

### Register TaskMachine console helper
```php
$app = new \Symfony\Component\Console\Application;
$helperSet = $app->getHelperSet();
$helperSet->set(new \TaskMachineKit\Console\Helper\TaskMachineHelper);
$app->setHelperSet($helperSet);
$app->addCommands([
    // my command list ...
]);
$app->run();
```
### Use TaskMachine in your commands
```php
protected function execute(
    \Symfony\Component\Console\Input\InputInterface $input, 
    \Symfony\Component\Console\Output\OutputInterface $output
) {
    //Helper is already InputAware
    $tmb = $this->getHelper('taskmachine')->getBuilder($output);
    
    // Create some machines
    $tm = $tmb->machine('machine')
        ->askChoice([
            'initial' => true,
            'input' => ['choices => ['a', 'b']],
            'transition' => 'askConfirmation'
        ])
        ->askConfirmation(['final' => true])
        ->build();
    
    // Run
    $output = $tm->run();
}
```

### Interopability with your own Auryn dependency injector
```php
$helperSet->set(new \TaskMachineKit\Console\Helper\TaskMachineHelper($myInjector));
```

## Commands
The following handlers are provided in the kit.

 - AskChoice
 - AskConfirmation
 - FindFiles
 
