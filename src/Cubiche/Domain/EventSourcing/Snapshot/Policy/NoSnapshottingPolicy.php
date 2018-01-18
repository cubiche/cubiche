<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Snapshot\Policy;

use Cubiche\Domain\EventSourcing\AggregateRootInterface;

/**
 * NoSnapshottingPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class NoSnapshottingPolicy implements SnapshottingPolicyInterface
{
    /**
     * {@inheritdoc}
     */
    public function shouldCreateSnapshot(AggregateRootInterface $aggregateRoot)
    {
        return false;
    }
}
