<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

/**
 * This Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class This extends Selector
{
    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor)
    {
        return $visitor->visitThis($this);
    }

    /**
     * {@inheritdoc}
     */
    public function select(SelectorInterface $selector)
    {
        return $selector;
    }
}
