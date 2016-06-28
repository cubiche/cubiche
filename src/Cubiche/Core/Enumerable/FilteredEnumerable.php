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
 * Filtered Enumerable Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class FilteredEnumerable extends EnumerableDecorator
{
    /**
     * @var callable
     */
    protected $predicate;

    /**
     * @param EnumerableInterface $enumerable
     * @param callable            $predicate
     */
    public function __construct(EnumerableInterface $enumerable, callable $predicate)
    {
        parent::__construct($enumerable);

        $this->predicate = $predicate;
    }

    /**
     * @return callable
     */
    public function predicate()
    {
        return $this->predicate;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \CallbackFilterIterator(parent::getIterator(), $this->predicate());
    }
}
