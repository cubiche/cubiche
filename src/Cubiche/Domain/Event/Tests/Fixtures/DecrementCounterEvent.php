<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Event\Tests\Fixtures;

use Cubiche\Domain\Event\DomainEvent;

/**
 * DecrementCounterEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DecrementCounterEvent extends DomainEvent
{
    /**
     * @var int
     */
    protected $step;

    /**
     * IncrementCounterEvent constructor.
     *
     * @param $step
     */
    public function __construct($step = 1)
    {
        $this->step = $step;
    }

    /**
     * @return int
     */
    public function step()
    {
        return $this->step;
    }
}
