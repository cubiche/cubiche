<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable;

use Cubiche\Core\Delegate\Delegate;

/**
 * Selector Comparator class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorComparator extends Comparator
{
    /**
     * @var Delegate
     */
    protected $selector;

    /**
     * @var Direction
     */
    protected $direction;

    /**
     * @param callable  $selector
     * @param Direction $direction
     */
    public function __construct(callable $selector, Direction $direction = null)
    {
        $this->selector = new Delegate($selector);
        $this->direction = Direction::ensure($direction);
    }

    /**
     * @return callable
     */
    public function selector()
    {
        return $this->selector->target();
    }

    /**
     * @return \Cubiche\Core\Comparable\Direction
     */
    public function direction()
    {
        return $this->direction;
    }

    /**
     * {@inheritdoc}
     */
    public function compare($a, $b)
    {
        return $this->direction()->value() * parent::compare(
            $this->selector->__invoke($a),
            $this->selector->__invoke($b)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function reverse()
    {
        return new self($this->selector(), $this->direction()->reverse());
    }
}
