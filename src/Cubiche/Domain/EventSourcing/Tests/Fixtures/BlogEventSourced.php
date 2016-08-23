<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRoot;
use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;
use Cubiche\Domain\EventSourcing\Metadata\Annotations as ES;
use Cubiche\Domain\Model\AggregateRoot;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * BlogEventSourced class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @ES\Migratable
 */
class BlogEventSourced extends AggregateRoot implements EventSourcedAggregateRootInterface
{
    use EventSourcedAggregateRoot;

    /**
     * Blog constructor.
     *
     * @param PostId $id
     */
    public function __construct(PostId $id)
    {
        $this->id = $id;
    }
}
