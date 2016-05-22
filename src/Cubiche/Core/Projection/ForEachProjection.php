<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection;

use Cubiche\Core\Selector\SelectorInterface;

/**
 * For Each Projection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ForEachProjection extends Projection
{
    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @var ProjectionInterface
     */
    protected $projection;

    /**
     * @param SelectorInterface   $selector
     * @param ProjectionInterface $projection
     */
    public function __construct(SelectorInterface $selector, ProjectionInterface $projection)
    {
        $this->selector = $selector;
        $this->projection = $projection;
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        $values = $this->selector()->apply($value);
        if (!\is_array($values) && !$value instanceof \Traversable) {
            $values = array($values);
        }

        foreach ($values as $value) {
            //yield from $this->projection()->project($item); in PHP 7
            foreach ($this->projection()->project($value) as $item) {
                yield $item;
            }
        }
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectionInterface
     */
    public function projection()
    {
        return $this->projection;
    }

    /**
     * {@inheritdoc}
     */
    public function properties()
    {
        return $this->projection()->properties();
    }
}
