<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Bundle\CoreBundle\Command;

use MongoDB\Database;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * DropSchemaCommand class.
 *
 * @author Ivan Suárez Jerez <ivan@howaboutsales.com>
 */
class DropSchemaCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cubiche:mongodb:schema-drop')
            ->setDescription('Drops the default document manager schema.')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Cleaning the </info>mongodb <info>databases</info>');

        $dm = $this->getContainer()->get('cubiche.mongodb.document_manager');
        $eventStoreConnection = $this->getContainer()->get('cubiche.mongodb.connection.event_store');

        // drop the read model database
        $schemaManager = $dm->getSchemaManager();
        $schemaManager->dropDatabases();

        // drop write model event store
        $eventStoreDatabase = new Database($eventStoreConnection->manager(), $eventStoreConnection->database());
        $eventStoreDatabase->drop();
    }
}
