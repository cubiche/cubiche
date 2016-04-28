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
 * Count Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Count extends Selector
{
    /**
     * {@inheritdoc}
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor)
    {
        return $visitor->visitCount($this);
    }

    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return \count($value);
    }
}
