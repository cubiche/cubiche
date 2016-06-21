<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection;

use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Selector\NamedSelectorInterface;
use Cubiche\Core\Selector\Selectors;

/**
 * Property Projector Builder Class.
 *
 * @method Cubiche\Core\Projection\PropertyProjectorBuilder as(string $name);
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyProjectorBuilder extends ExtendedProjector
{
    /**
     * @var PropertyProjector
     */
    protected $projector;

    /**
     * @var Selector
     */
    protected $currentSelector;

    /**
     * @var int
     */
    private $nonameCount = 0;

    /**
     * @param SelectorInterface $selector
     */
    public function __construct(SelectorInterface $selector)
    {
        parent::__construct($this);

        $this->projector = new PropertyProjector();
        $this->select($selector);
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, array $arguments)
    {
        if ($method === 'as') {
            return \call_user_func_array(array($this, 'asName'), $arguments);
        }

        throw new \BadMethodCallException(\sprintf('Call to undefined method %s::%s', \get_class($this), $method));
    }

    /**
     * @return \Cubiche\Core\Projection\PropertyProjector
     */
    public function projector()
    {
        $this->addCurrentSelector();

        return $this->projector;
    }

    /**
     * @param SelectorInterface $selector
     *
     * @return $this
     */
    public function select(SelectorInterface $selector)
    {
        $this->addCurrentSelector();
        $this->currentSelector = $selector instanceof Selectors ? $selector->selector() : $selector;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function castTo($class)
    {
        $this->projector()->setClass($class);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        return $this->projector()->project($value);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    private function asName($name)
    {
        if ($this->currentSelector === null) {
            throw new \LogicException(
                \sprintf('The %s::as() method must be called after %s::select() method', self::class, self::class)
            );
        }

        $this->addProperty($name);

        return $this;
    }

    /**
     * @param string $name
     *
     * @throws \LogicException
     */
    private function addProperty($name = null)
    {
        if ($name === null) {
            $name = $this->currentSelector instanceof NamedSelectorInterface ?
                $this->currentSelector->name() :
                'field'.$this->nonameCount++
            ;
        }

        $this->projector->add(new Property($this->currentSelector, $name));
        $this->currentSelector = null;
    }

    private function addCurrentSelector()
    {
        if ($this->currentSelector !== null) {
            $this->addProperty();
        }
    }
}
