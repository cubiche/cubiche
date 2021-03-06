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

use Cubiche\Core\Predicate\Predicate;
use Cubiche\Core\Predicate\PredicateInterface;

/**
 * Filtered Enumerable class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class FilteredEnumerable extends EnumerableDecorator
{
    /**
     * @var PredicateInterface
     */
    protected $predicate;

    /**
     * @param array|\Traversable $enumerable
     * @param callable           $predicate
     */
    public function __construct($enumerable, callable $predicate)
    {
        parent::__construct($enumerable);

        $this->predicate = Predicate::from($predicate);
    }

    /**
     * @return \Cubiche\Core\Predicate\PredicateInterface
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

    /**
     * {@inheritdoc}
     */
    public function where(callable $predicate)
    {
        return new self($this->enumerable(), $this->predicate()->andPredicate($predicate));
    }
}
