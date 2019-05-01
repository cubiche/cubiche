<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Repository\MongoDB\Factory;

use Cubiche\Core\Bus\Message\Publisher\MessagePublisherInterface;
use Cubiche\Infrastructure\Repository\MongoDB\AggregateRepository;
use Cubiche\Infrastructure\MongoDB\DocumentManager;
use Cubiche\Infrastructure\MongoDB\Repository;

/**
 * AggregateRepositoryFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AggregateRepositoryFactory implements AggregateRepositoryFactoryInterface
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var MessagePublisherInterface
     */
    protected $publisher;

    /**
     * AggregateRepositoryFactory constructor.
     *
     * @param DocumentManager           $dm
     * @param MessagePublisherInterface $publisher
     */
    public function __construct(DocumentManager $dm, MessagePublisherInterface $publisher)
    {
        $this->dm = $dm;
        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function create($documentName)
    {
        return new AggregateRepository(
            new Repository($this->dm, $documentName),
            $this->publisher
        );
    }
}
