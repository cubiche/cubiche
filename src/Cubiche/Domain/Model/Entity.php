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
    protected function __construct(IdInterface $id)
    {
        $this->id = $id;
    }

    /**
     * @param IdInterface $id
     *
     * @return static
     */
    public static function create(IdInterface $id)
    {
        return new static($id);
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
}
