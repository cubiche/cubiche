<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventPublisher\Tests\Fixtures;

use Cubiche\Domain\EventPublisher\DomainEvent;

/**
 * IncrementCounterEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class IncrementCounterEvent extends DomainEvent
{
    /**
     * IncrementCounterEvent constructor.
     *
     * @param $step
     */
    public function __construct($step = 1)
    {
        parent::__construct();

        $this->setPayload('step', $step);
    }

    /**
     * @return int
     */
    public function step()
    {
        return $this->getPayload('step');
    }
}
