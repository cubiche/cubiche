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

use Cubiche\Domain\Collections\Specification\SelectorInterface;
use Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface;

/**
 * Composite Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Composite extends Selector
{
    /**
     * @var SelectorInterface
     */
    protected $valueSelector;

    /**
     * @var SelectorInterface
     */
    protected $applySelector;

    /**
     * @param SelectorInterface $selector1
     * @param SelectorInterface $selector2
     */
    public function __construct(SelectorInterface $valueSelector, SelectorInterface $applySelector)
    {
        $this->valueSelector = $valueSelector;
        $this->applySelector = $applySelector;
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\SelectorInterface
     */
    public function valueSelector()
    {
        return $this->valueSelector;
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\SelectorInterface
     */
    public function applySelector()
    {
        return $this->applySelector;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Specification::visit()
     */
    public function visit(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitComposite($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Selector::apply()
     */
    public function apply($value)
    {
        return $this->applySelector->apply($this->valueSelector->apply($value));
    }
}
