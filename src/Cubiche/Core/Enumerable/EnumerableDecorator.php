<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable;

/**
 * Abstract Enumerable Decorator class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class EnumerableDecorator extends AbstractEnumerable
{
    /**
     * @var EnumerableInterface
     */
    protected $enumerable;

    /**
     * @param array|\Traversable $enumerable
     */
    public function __construct($enumerable)
    {
        $this->enumerable = Enumerable::from($enumerable);
    }

    /**
     * @return EnumerableInterface
     */
    public function enumerable()
    {
        return $this->enumerable;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->enumerable()->getIterator();
    }
}
