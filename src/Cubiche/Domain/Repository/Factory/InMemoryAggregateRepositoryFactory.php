<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Repository\Factory;

use Cubiche\Core\Bus\Recorder\MessagePublisher;
use Cubiche\Core\Bus\Recorder\MessagePublisherInterface;
use Cubiche\Domain\Repository\InMemory\InMemoryAggregateRepository;

/**
 * InMemoryAggregateRepositoryFactory Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryAggregateRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * @var MessagePublisherInterface
     */
    protected $publisher;

    /**
     * InMemoryAggregateRepositoryFactory constructor.
     *
     * @param MessagePublisherInterface $publisher
     */
    public function __construct(MessagePublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function create($modelName)
    {
        return new InMemoryAggregateRepository($this->publisher);
    }
}
