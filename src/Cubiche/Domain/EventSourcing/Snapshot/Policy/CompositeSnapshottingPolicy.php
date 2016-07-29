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

use Cubiche\Domain\EventSourcing\EventSourcedAggregateRootInterface;

/**
 * CompositeSnapshottingPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CompositeSnapshottingPolicy implements SnapshottingPolicyInterface
{
    /**
     * @var SnapshottingPolicyInterface[]
     */
    protected $policies = [];

    /**
     * CompositeSnapshottingPolicy constructor.
     *
     * @param SnapshottingPolicyInterface[] $policies
     */
    public function __construct(array $policies)
    {
        foreach ($policies as $policy) {
            if (!$policy instanceof SnapshottingPolicyInterface) {
                throw new \InvalidArgumentException();
            }

            $this->policies[] = $policy;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function shouldCreateSnapshot(EventSourcedAggregateRootInterface $eventSourcedAggregateRoot)
    {
        foreach ($this->policies as $policy) {
            if (!$policy->shouldCreateSnapshot($eventSourcedAggregateRoot)) {
                return false;
            }
        }

        return true;
    }
}
