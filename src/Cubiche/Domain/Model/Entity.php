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

    /**
     * @param IdInterface $id
     */
    public function __construct(IdInterface $id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return $other instanceof static && $this->id()->equals($other->id());
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode()
    {
        return $this->id()->hashCode();
    }
}
