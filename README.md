# ConsoleMachine
### TaskMachine orchestration kit for Symfony Console.

## Integration
You can integrate TaskMachine into you app easily as follows:

### Register TaskMachine console helper
```php
$app = new Application;
$helperSet = $app->getHelperSet();
$helperSet->set(new TaskMachineHelper);
$app->setHelperSet($helperSet);
$app->addCommands([
    // my command list ...
]);
$app->run();
```
### Use TaskMachine in your commands
```php
protected function execute(InputInterface $input, OutputInterface $output)
{
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
$helperSet->set(new TaskMachineHelper($myInjector));
```

## Commands
The following handlers are provided in the kit.

 - AskChoice
 - AskConfirmation
 - FindFiles
 