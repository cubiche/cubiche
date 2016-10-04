<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Predicate;

use Cubiche\Core\Selector\Selector as SelectorBase;
use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Selector\Selectors;

/**
 * Selector Predicate class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorPredicate extends Predicate implements SelectorInterface
{
    use Constraint, Quantifier;

    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @param callable|mixed $selector
     */
    public function __construct($selector)
    {
        $this->selector = SelectorBase::from($selector);
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate($value)
    {
        return $this->apply($value);
    }

    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return $this->selector()->apply($value);
    }

    /**
     * {@inheritdoc}
     */
    public function select($selector)
    {
        return new static($this->selector()->select($selector));
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @param string $name
     *
     * @return \Cubiche\Core\Predicate\SelectorPredicate
     */
    public function key($name)
    {
        return $this->select(Selectors::key($name)->selector());
    }

    /**
     * @param string $name
     *
     * @return \Cubiche\Core\Predicate\SelectorPredicate
     */
    public function property($name)
    {
        return $this->select(Selectors::property($name)->selector());
    }

    /**
     * @param string $name
     *
     * @return \Cubiche\Core\Predicate\SelectorPredicate
     */
    public function method($name)
    {
        return $this->select(Selectors::method($name)->selector());
    }

    /**
     * @param callable $callback
     *
     * @return \Cubiche\Core\Predicate\SelectorPredicate
     */
    public function callback(callable $callback)
    {
        return $this->select(Selectors::callback($callback)->selector());
    }

    /**
     * @return \Cubiche\Core\Predicate\SelectorPredicate
     */
    public function count()
    {
        return $this->select(Selectors::count()->selector());
    }
}
