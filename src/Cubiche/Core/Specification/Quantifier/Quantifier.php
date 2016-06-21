<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification\Quantifier;

use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Specification\Specification;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Abstract Quantifier Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Quantifier extends Specification implements QuantifierInterface
{
    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @var SpecificationInterface
     */
    protected $specification;

    /**
     * @param SelectorInterface      $selector
     * @param SpecificationInterface $specification
     */
    public function __construct(SelectorInterface $selector, SpecificationInterface $specification)
    {
        $this->selector = $selector;
        $this->specification = $specification;
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @return \Cubiche\Core\Specification\SpecificationInterface
     */
    public function specification()
    {
        return $this->specification;
    }

    /**
     * @param mixed $value
     *
     * @return Generator
     */
    protected function evaluationIterator($value)
    {
        $items = $this->selector()->apply($value);
        if (!\is_array($items) && !$value instanceof \Traversable) {
            $items = array($items);
        }

        foreach ($items as $item) {
            yield $this->specification()->evaluate($item);
        }
    }
}
