<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Units;

use Cubiche\Domain\Model\NativeValueObjectInterface;
use Cubiche\Domain\Tests\Units\TestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;

/**
 * NativeValueObjectTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class NativeValueObjectTestCase extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        \Closure $reflectionClassFactory = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );

        $this->getAssertionManager()
            ->setHandler(
                'randomNativeValue',
                function () {
                    return $this->randomNativeValue();
                }
            )
            ->setHandler(
                'invalidNativeValue',
                function () {
                    return $this->invalidNativeValue();
                }
            )
            ->setHandler(
                'uniqueNativeValue',
                function () {
                    return $this->uniqueNativeValue();
                }
            )
            ->setHandler(
                'fromNative',
                function ($value) {
                    return $this->fromNative($value);
                }
            )
        ;
    }

    /**
     * @return mixed
     */
    abstract protected function randomNativeValue();

    /**
     * @return mixed
     */
    abstract protected function invalidNativeValue();

    /**
     * @return mixed
     */
    abstract protected function uniqueNativeValue();

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    abstract protected function fromNative($value);

    /*
     * Test fromNative/toNative.
     */
    public function testFromNativeToNative()
    {
        $this
            ->given(
                $native = $this->randomNativeValue(),
                $valueObject = $this->fromNative($native)
            )
            ->then
                ->object($valueObject)
                    ->isInstanceOf(NativeValueObjectInterface::class)
                ->variable($native)
                    ->isEqualTo($valueObject->toNative())
        ;

        $this
            ->given($native = $this->invalidNativeValue())
            ->then
                ->exception(function () use ($native) {
                    $this->fromNative($native);
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }

    /**
     * Test equals method.
     */
    public function testEquals()
    {
        $this
            ->given(
                $native1 = $this->uniqueNativeValue(),
                $native2 = $this->randomNativeValue(),
                $valueObject1 = $this->fromNative($native1),
                $valueObject2 = $this->fromNative($native2)
            )
            ->then
                ->boolean($valueObject1->equals($valueObject1))
                    ->isTrue()
                ->boolean($valueObject1->equals($valueObject2))
                    ->isFalse()
                ->string($valueObject1->__toString())
                    ->isEqualTo(\strval($native1))
        ;
    }
}
