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

use Cubiche\Core\Delegate\Delegate;

/**
 * Custom Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Custom extends Selector
{
    /**
     * @var Delegate
     */
    protected $delegate;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->delegate = new Delegate($callable);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorInterface::apply()
     */
    public function apply($value)
    {
        return $this->delegate->__invoke($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorInterface::acceptSelectorVisitor()
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor)
    {
        return $visitor->visitCustom($this);
    }
}
