<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Fixtures;

use Cubiche\Domain\EventSourcing\AggregateRoot;

/**
 * BlogEventSourced class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BlogEventSourced extends AggregateRoot
{
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
