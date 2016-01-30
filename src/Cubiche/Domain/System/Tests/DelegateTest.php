<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\Model\Tests\TestCase;
use Cubiche\Domain\Delegate\Delegate;

/**
 * Delegate Test.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DelegateTest extends TestCase
{
    /**
     * @test
     */
    public function isCallable()
    {
        $delegate = new Delegate(function () {
            return 'foo';
        });

        $this->assertTrue(is_callable($delegate));
    }

    /**
     * @test
     */
    public function closureConstructor()
    {
        $delegate = new Delegate(function () {
            return 'foo';
        });
        $this->assertEquals('foo', $delegate());
    }

    /**
     * @test
     */
    public function methodConstructor()
    {
        $delegate = new Delegate(array($this, 'method'));
        $this->assertEquals('foo', $delegate());
    }

    /**
     * @test
     */
    public function functionConstructor()
    {
        $delegate = new Delegate('is_null');
        $this->assertTrue($delegate(null));
        $this->assertFalse($delegate($this));
    }

    /**
     * @test
     */
    public function constructorNotCallable()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new Delegate(array($this, 'foo'));
    }

    /**
     * @test
     */
    public function invokeMagic()
    {
        $delegate = new Delegate(function () {
            return 'foo';
        });
        $this->assertEquals('foo', $delegate->__invoke());
    }

    /**
     * @test
     */
    public function callUserFunc()
    {
        $delegate = new Delegate(function () {
            return 'foo';
        });
        $this->assertEquals('foo', call_user_func($delegate));
    }

    /**
     * @test
     */
    public function withParameters()
    {
        $foo = 'foo';
        $delegate = new Delegate(function ($param) {
            return $param;
        });
        $this->assertEquals($foo, $delegate($foo));
    }

    /**
     * @test
     */
    public function withExternalParameters()
    {
        $foo = 'foo';
        $delegate = new Delegate(function () use ($foo) {
            return $foo;
        });

        $this->assertEquals($foo, $delegate());
    }

    /**
     * @test
     */
    public function fromClosure()
    {
        $delegate = Delegate::fromClosure(function () {
            return 'foo';
        });
        $this->assertInstanceOf(Delegate::class, $delegate);
        $this->assertEquals('foo', $delegate());
    }

    /**
     * @test
     */
    public function fromFunction()
    {
        $delegate = Delegate::fromFunction('is_null');
        $this->assertInstanceOf(Delegate::class, $delegate);
        $this->assertTrue($delegate(null));
        $this->assertFalse($delegate($this));
    }

    /**
     * @test
     */
    public function fromMethod()
    {
        $delegate = Delegate::fromMethod($this, 'method');
        $this->assertInstanceOf(Delegate::class, $delegate);
        $this->assertEquals('foo', $delegate());
    }

    /**
     * @return string
     */
    public function method()
    {
        return 'foo';
    }
}
