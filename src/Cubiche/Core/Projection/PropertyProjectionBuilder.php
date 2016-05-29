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

/**
 * Property Projection Builder Class.
 *
 * @method PropertyProjectionBuilder as(string $name)
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PropertyProjectionBuilder extends ExtendedProjection
{
    /**
     * @var PropertyProjection
     */
    protected $projection;

    /**
     * @var Selector
     */
    protected $currentSelector;

    /**
     * @var int
     */
    private $nonameCount;

    /**
     * @param SelectorInterface $selector
     */
    public function __construct(SelectorInterface $selector)
    {
        parent::__construct($this);

        $this->projection = new PropertyProjection();
        $this->currentSelector = $selector;
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
     * @return \Cubiche\Core\Projection\PropertyProjection
     */
    public function projection()
    {
        $this->addCurrentSelector();

        return $this->projection;
    }

    /**
     * @param SelectorInterface $selector
     *
     * @return $this
     */
    public function select(SelectorInterface $selector)
    {
        $this->currentSelector = $selector;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        return $this->projection()->project($value);
    }

    /**
     * @return Property[]
     */
    public function properties()
    {
        return $this->projection()->properties();
    }

    /**
     * @param string $name
     *
     * @return \Cubiche\Core\Projection\PropertyProjectionBuilder
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

        $this->projection->add(new Property($name, $this->currentSelector));
        $this->currentSelector = null;
    }

    private function addCurrentSelector()
    {
        if ($this->currentSelector !== null) {
            $this->addProperty();
        }
    }
}
