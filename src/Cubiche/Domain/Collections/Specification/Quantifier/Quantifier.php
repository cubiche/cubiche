<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Specification\Quantifier;

use Cubiche\Domain\Collections\Specification\QuantifierInterface;
use Cubiche\Domain\Collections\Specification\SelectorInterface;
use Cubiche\Domain\Collections\Specification\Specification;
use Cubiche\Domain\Collections\Specification\SpecificationInterface;

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
     * @return \Cubiche\Domain\Collections\Specification\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @return \Cubiche\Domain\Collections\Specification\SpecificationInterface
     */
    public function specification()
    {
        return $this->specification;
    }
}
