<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model;

/**
 * Abstract Aggregate Root Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class AggregateRoot extends Entity implements AggregateRootInterface
{
    /**
     * @param IdInterface $id
     */
    public function __construct(IdInterface $id)
    {
        parent::__construct($id);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\EquatableInterface::equals()
     */
    public function equals($other)
    {
        return $other instanceof self && $this->id()->equals($other->id());
    }
}
