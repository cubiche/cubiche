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
 * Abstract Entity Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Entity implements EntityInterface
{
    /**
     * @var IdInterface
     */
    protected $id;

    /**S
     * @param IdInterface $id
     */
    public function __construct(IdInterface $id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\EntityInterface::id()
     */
    public function id()
    {
        return $this->id;
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
