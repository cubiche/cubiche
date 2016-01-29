<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model\Tests;

use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * Native Value Object Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class NativeValueObjectTestCase extends ValueObjectTestCase
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @param string $className
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($className, $name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Tests\Domain\Core\ValueObjectTestCase::firstValue()
     */
    protected function firstValue()
    {
        return $this->fromNativeValue($this->firstNativeValue());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Jadddp\Tests\Domain\Core\ValueObjectTestCase::secondValue()
     */
    protected function secondValue()
    {
        return $this->fromNativeValue($this->secondNativeValue());
    }

    /**
     * @param mixed $value
     *
     * @return NativeValueObjectInterface
     */
    protected function fromNativeValue($value)
    {
        $class = new \ReflectionClass($this->className);
        $method = $class->getMethod('fromNative');

        return $method->invoke(null, $value);
    }

    /**
     * @return mixed
     */
    abstract protected function firstNativeValue();

    /**
     * @return mixed
     */
    abstract protected function secondNativeValue();

    /**
     * @test
     */
    public function fromNative()
    {
        $value = $this->firstValue();

        $this->assertInstanceOf(NativeValueObjectInterface::class, $value);
        $this->assertInstanceOf($this->className, $value);
        $this->assertEquals($value->toNative(), $this->firstNativeValue());
    }

    /**
     * @test
     */
    public function toNative()
    {
        $value = $this->firstValue();
        $this->assertEquals($value->toNative(), $this->firstNativeValue());
    }
}
