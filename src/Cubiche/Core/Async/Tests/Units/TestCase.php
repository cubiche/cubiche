<?php

/**
 * This file is part of the Cubiche/Async component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Async\Tests\Units;

use Cubiche\Core\Delegate\Delegate;
use Cubiche\Tests\TestCase as BaseTestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\mock\aggregator as MockAggregator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * Test Case class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param Closure   $reflectionClassFactory
     * @param Closure   $phpExtensionFactory
     * @param Analyzer  $analyzer
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        \Closure $reflectionClassFactory = null,
        \Closure $phpExtensionFactory = null,
        Analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );

        $this
            ->getAssertionManager()
                ->setHandler('delegateCall', function (MockAggregator $mock) {
                    return $this->delegateCall($mock);
                })
        ;
    }

    /**
     * @param callable $callable
     *
     * @return \Cubiche\Core\Delegate\Delegate
     */
    protected function callableMock(callable $callable)
    {
        $mockName = '\mock\\'.Delegate::class;

        return new $mockName($callable);
    }

    /**
     * @param mixed $return
     *
     * @return \Cubiche\Core\Delegate\Delegate
     */
    protected function delegateMock($return = null)
    {
        return $this->callableMock(function ($value = null) use ($return) {
            return $return === null ? $value : $return;
        });
    }

    /**
     * @param mixed $return
     *
     * @return \Cubiche\Core\Delegate\Delegate
     */
    protected function delegateMockWithReturn($return)
    {
        return $this->delegateMock($return);
    }

    /**
     * @param \Exception $e
     *
     * @return \Cubiche\Core\Delegate\Delegate
     */
    protected function delegateMockWithException(\Exception $e)
    {
        return $this->callableMock(function () use ($e) {
            throw $e;
        });
    }

    /**
     * @param MockAggregator $mock
     *
     * @return mixed
     */
    protected function delegateCall(MockAggregator $mock)
    {
        return $this->mock($mock)->call('__invoke');
    }
}
