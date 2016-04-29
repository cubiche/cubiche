<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Selector;

use Cubiche\Core\Visitor\Visitee;
use Cubiche\Core\Visitor\VisitorInterface;

/**
 * Abstract Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Selector extends Visitee implements SelectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function select(SelectorInterface $selector)
    {
        return new Composite($this, $selector);
    }

    /**
     * @param string $key
     *
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function key($key)
    {
        return $this->select(new Key($key));
    }

    /**
     * @param string $property
     *
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function property($property)
    {
        return $this->select(new Property($property));
    }

    /**
     * @param string $method
     *
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function method($method)
    {
        return $this->select(new Method($method));
    }

    /**
     * @param callable $callable
     *
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function custom(callable $callable)
    {
        return $this->select(new Custom($callable));
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function count()
    {
        return $this->select(new Count());
    }

    /**
     * {@inheritdoc}
     */
    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof SelectorVisitorInterface) {
            return $this->acceptSelectorVisitor($visitor);
        }

        return parent::accept($visitor);
    }
}
