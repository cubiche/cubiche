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

use Cubiche\Domain\Collections\Specification\SelectorInterface;
use Cubiche\Domain\Collections\Specification\SpecificationInterface;
use Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface;

/**
 * At Least Quantifier Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class AtLeast extends Quantifier
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @param int                    $count
     * @param SelectorInterface      $selector
     * @param SpecificationInterface $specification
     */
    public function __construct($count, SelectorInterface $selector, SpecificationInterface $specification)
    {
        parent::__construct($selector, $specification);

        $this->count = (int) $count;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\Specification::visit($visitor)
     */
    public function visit(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitAtLeast($this);
    }
}
