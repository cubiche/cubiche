<?php
//
///**
// * This file is part of the Cubiche package.
// *
// * Copyright (c) Cubiche
// *
// * For the full copyright and license information, please view the LICENSE
// * file that was distributed with this source code.
// */
//
//set_time_limit(0);
//$loaded = false;
//foreach (array(__DIR__ . '/../../autoload.php', __DIR__ . '/../../../../../vendor/autoload.php') as $file) {
//    if (file_exists($file)) {
//        require $file;
//        $loaded = true;
//        break;
//    }
//}
//
//if (!$loaded) {
//    die(
//        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
//        'wget http://getcomposer.org/composer.phar' . PHP_EOL .
//        'php composer.phar install' . PHP_EOL
//    );
//}
//
//use Cubiche\Core\Bus\Command\CommandBus;
//use Cubiche\Core\Console\ConsoleApplication;
//use Cubiche\Domain\EventPublisher\DomainEventPublisher;
//use Cubiche\Domain\EventSourcing\Migrations\Cli\ApplicationConfig;
//use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsGenerateCommand;
//use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsMigrateCommand;
//use Cubiche\Domain\EventSourcing\Migrations\Cli\Command\MigrationsStatusCommand;
//use Cubiche\Domain\EventSourcing\Migrations\Cli\MigrationsService;
//use Cubiche\Domain\EventSourcing\Migrations\Generator\MigrationGenerator;
//
///**
// * @param callable|ApplicationConfig $config
// *
// * @return ConsoleApplication
// */
//function createApplication($config)
//{
//    $commandBus = CommandBus::create();
//    $eventBus = DomainEventPublisher::eventBus();
//
//    $migrationsService = new MigrationsService(new MigrationGenerator(__DIR__.'/Foo'));
//
//    $commandBus->addHandler(MigrationsGenerateCommand::class, $migrationsService);
//    $commandBus->addHandler(MigrationsMigrateCommand::class, $migrationsService);
//    $commandBus->addHandler(MigrationsStatusCommand::class, $migrationsService);
//
//    return new ConsoleApplication($config, $commandBus, $eventBus);
//}
//
//$cli = createApplication(new ApplicationConfig());
//$cli->run();
