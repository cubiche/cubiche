<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification\Selector;

use Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface;
use Cubiche\Domain\System\Delegate;

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
    public function __construct($callable)
    {
        $this->delegate = new Delegate($callable);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Selector::apply()
     */
    public function apply($value)
    {
        return $this->delegate->__invoke($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Specification::visit()
     */
    public function visit(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitCustom($this);
    }
}
