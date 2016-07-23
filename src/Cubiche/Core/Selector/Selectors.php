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

use Cubiche\Core\Visitor\VisitorInterface;

/**
 * Selector Builder Class.
 *
 * @method static Selectors key(string $name)
 * @method static Selectors property(string $name)
 * @method static Selectors method(string $name)
 * @method static Selectors callback(callable $callback)
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
            self::$factory = new SelectorFactory(__NAMESPACE__);
        }

        return self::$factory;
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
     * @param callable|mixed $selector
     *
     * @return \Cubiche\Core\Selector\Selectors
     */
    public static function from($selector)
    {
        return new static(Selector::from($selector));
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
    public function select($selector)
    {
        $this->selector = $this->selector()->select($selector);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(VisitorInterface $visitor)
    {
        return $this->delegateAccept($this->selector(), $visitor, \func_get_args());
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @return \Cubiche\Core\Selector\Selectors
     */
    public static function false()
    {
        return self::value(false);
    }

    /**
     * @return \Cubiche\Core\Selector\Selectors
     */
    public static function true()
    {
        return self::value(true);
    }

    /**
     * @return \Cubiche\Core\Selector\Selectors
     */
    public static function null()
    {
        return self::value(null);
    }
}
