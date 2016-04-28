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
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function valueSelector()
    {
        return $this->valueSelector;
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function applySelector()
    {
        return $this->applySelector;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor)
    {
        return $visitor->visitComposite($this);
    }

    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return $this->applySelector->apply($this->valueSelector->apply($value));
    }
}
