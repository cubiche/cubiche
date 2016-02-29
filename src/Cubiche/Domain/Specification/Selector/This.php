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

use Cubiche\Domain\Specification\SpecificationVisitorInterface;

/**
 * This Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class This extends Selector
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\Selector::apply()
     */
    public function apply($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationInterface::accept()
     */
    public function accept(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitThis($this);
    }
}
