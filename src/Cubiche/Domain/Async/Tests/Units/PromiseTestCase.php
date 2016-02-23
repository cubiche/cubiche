<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Async\Tests\Units;

use Cubiche\Domain\Async\Promise;
use Cubiche\Domain\Async\PromiseInterface;
use Cubiche\Domain\Delegate\Delegate;
use Cubiche\Domain\Tests\Units\TestCase;
use mageekguy\atoum\adapter;
use mageekguy\atoum\annotations\extractor;
use mageekguy\atoum\asserter\generator;
use mageekguy\atoum\mock;
use mageekguy\atoum\test\assertion\manager;

/**
 * PromiseTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PromiseTestCase extends TestCase
{
    /**
     * @var PromiseInterface
     */
    protected $promise;

    /**
     * @var Delegate
     */
    protected $resolve;

    /**
     * @var Delegate
     */
    protected $reject;

    /**
     * @var Delegate
     */
    protected $notify;

    /**
     * @var Delegate
     */
    protected $cancel;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        adapter $adapter = null,
        extractor $annotationExtractor = null,
        generator $asserterGenerator = null,
        manager $assertionManager = null,
        \closure $reflectionClassFactory = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );

        $test = $this;

        $this->getAssertionManager()
            ->setHandler(
                'promise',
                function () use ($test) {
                    return new Promise(
                        new Delegate(function (Delegate $delegate) use ($test) {
                            $test->resolve = $delegate;
                        }),
                        new Delegate(function (Delegate $delegate) use ($test) {
                            $test->reject = $delegate;
                        }),
                        new Delegate(function (Delegate $delegate) use ($test) {
                            $test->notify = $delegate;
                        }),
                        new Delegate(function (Delegate $delegate) use ($test) {
                            $test->cancel = $delegate;
                        })
                    );
                }
            )
            ->setHandler(
                'resolve',
                function ($value = null) use ($test) {
                    $test->resolve->__invoke($value);
                }
            )
            ->setHandler(
                'reject',
                function ($value = null) use ($test) {
                    $test->reject->__invoke($value);
                }
            )
            ->setHandler(
                'notify',
                function ($value = null) use ($test) {
                    $test->notify->__invoke($value);
                }
            )
            ->setHandler(
                'cancel',
                function ($value = null) use ($test) {
                    $test->cancel->__invoke($value);
                }
            )
            ->setHandler(
                'delegateMock',
                function () {
                    return new \mock\Cubiche\Domain\Delegate\Delegate(function ($value = null) {
                    });
                }
            )
            ->setHandler(
                'delegateCall',
                function (mock\aggregator $mock) use ($test) {
                    return $test
                        ->MockBuilder($mock)
                        ->call('__invoke')
                    ;
                }
            )
        ;
    }
}
