<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\EventSourcing;

use Cubiche\Domain\Model\AggregateRootInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * Snapshot class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Snapshot
{
    /**
     * @var StringLiteral
     */
    protected $version;

    /**
     * @var AggregateRootInterface
     */
    protected $aggregate;

    /**
     * Snapshot constructor.
     *
     * @param StringLiteral          $version
     * @param AggregateRootInterface $aggregate
     */
    public function __construct(StringLiteral $version, AggregateRootInterface $aggregate)
    {
        $this->version = $version;
        $this->aggregate = $aggregate;
    }

    /**
     * @return StringLiteral
     */
    public function className()
    {
        return StringLiteral::fromNative(
            get_class($this->aggregate())
        );
    }

    /**
     * @return StringLiteral
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * @return AggregateRootInterface
     */
    public function aggregate()
    {
        return $this->aggregate;
    }
}
