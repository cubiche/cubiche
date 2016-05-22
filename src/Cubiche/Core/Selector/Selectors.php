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

/**
 * Selector Builder Class.
 *
 * @method static Selectors key(string $name)
 * @method static Selectors property(string $name)
 * @method static Selectors method(string $name)
 * @method static Selectors custom(callable $callable)
 * @method static Selectors value($value)
 * @method static Selectors count()
 * @method static Selectors composite(SelectorInterface $x, SelectorInterface $y)
 * @method static Selectors this()
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Selectors extends Selector
{
    /**
     * @var SelectorFactoryInterface
     */
    protected static $factory;

    /**
     * @var SelectorInterface
     */
    private $selector;

    /**
     * @return \Cubiche\Core\Selector\SelectorFactoryInterface
     */
    protected static function factory()
    {
        if (self::$factory === null) {
            self::setFactory(new SelectorFactory(__NAMESPACE__));
        }

        return self::$factory;
    }

    /**
     * @param SelectorFactoryInterface $factory
     */
    public static function setFactory(SelectorFactoryInterface $factory)
    {
        self::$factory = $factory;
    }

    /**
     * @param string $selectorClass
     * @param string $selectorName
     */
    public static function addSelector($selectorClass, $selectorName = null)
    {
        self::factory()->addSelector($selectorClass, $selectorName);
    }

    /**
     * @param string $namespace
     */
    public static function addNamespace($namespace)
    {
        self::factory()->addNamespace($namespace);
    }

    /**
     * @param SelectorInterface $selector
     */
    protected function __construct(SelectorInterface $selector)
    {
        $this->selector = $selector;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return new static(self::factory()->create($method, $arguments));
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->select(self::factory()->create($method, $arguments));
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
    public function select(SelectorInterface $selector)
    {
        $this->selector = $this->selector()->select($selector);

        return $this;
    }

    /**
     * @param SelectorVisitorInterface $visitor
     *
     * @return mixed
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor)
    {
        return $this->selector()->acceptSelectorVisitor($visitor);
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }
}
