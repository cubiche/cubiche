<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Equatable\Tests\Units;

use Cubiche\Core\Equatable\Tests\Fixtures\Value;
use Cubiche\Tests\TestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * Equatable Interface Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class EquatableInterfaceTestCase extends TestCase
{
    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param \Closure  $reflectionClassFactory
     * @param \Closure  $phpExtensionFactory
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

        $this->getAsserterGenerator()->addNamespace('Cubiche\Core\Equatable\Tests\Asserters');
        $this->getAssertionManager()->setAlias('variable', 'VariableAsserter');
    }

    /**
     * Test equals.
     */
    public function testEquals()
    {
        $this
            /* @var \Cubiche\Core\Equatable\EquatableInterface $equalatable1 */
            ->given($equalatable1 = $this->newDefaultTestedInstance())
            /* @var \Cubiche\Core\Equatable\EquatableInterface $equalatable2 */
            ->given($equalatable2 = $this->newDefaultTestedInstance())
            ->given($equalatableObject = new Value(\uniqid()))
            ->then()
                ->boolean($equalatable1->equals($equalatable2))
                    ->isTrue()
                ->boolean($equalatable1->equals($equalatableObject))
                    ->isFalse()
        ;
    }
}
