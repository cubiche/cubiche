<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Specification\Selector;

use Cubiche\Domain\Specification\SelectorInterface;
use Cubiche\Domain\Specification\SpecificationVisitorInterface;

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
     * @param SelectorInterface $valueSelector
     * @param SelectorInterface $applySelector
     */
    public function __construct(SelectorInterface $valueSelector, SelectorInterface $applySelector)
    {
        $this->valueSelector = $valueSelector;
        $this->applySelector = $applySelector;
    }

    /**
     * @return \Cubiche\Domain\Specification\SelectorInterface
     */
    public function valueSelector()
    {
        return $this->valueSelector;
    }

    /**
     * @return \Cubiche\Domain\Specification\SelectorInterface
     */
    public function applySelector()
    {
        return $this->applySelector;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::accept()
     */
    public function accept(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitComposite($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\Selector::apply()
     */
    public function apply($value)
    {
        return $this->applySelector->apply($this->valueSelector->apply($value));
    }
}
