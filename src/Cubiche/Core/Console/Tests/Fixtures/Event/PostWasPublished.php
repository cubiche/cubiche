<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console\Tests\Fixtures\Event;

use Cubiche\Core\Console\Tests\Fixtures\PostId;
use Cubiche\Domain\Model\EventSourcing\EntityDomainEvent;

/**
 * PostWasPublished class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostWasPublished extends EntityDomainEvent
{
    /**
     * PostWasPublished constructor.
     *
     * @param PostId $id
     */
    public function __construct(PostId $id)
    {
        parent::__construct($id);
    }

    /**
     * @return PostId
     */
    public function id()
    {
        return $this->aggregateId();
    }
}
