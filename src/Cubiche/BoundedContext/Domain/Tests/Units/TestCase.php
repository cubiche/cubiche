<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Domain\Tests\Units;

use Closure;
use Cubiche\Tests\TestCase as BaseTestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * TestCase class.
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
        Closure $reflectionClassFactory = null,
        Closure $phpExtensionFactory = null,
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
        $this->getAsserterGenerator()->addNamespace('Cubiche\Core\Collections\Tests\Asserters');

        $this->getAssertionManager()->setAlias('variable', 'VariableAsserter');
        $this->getAssertionManager()->setAlias('object', 'ObjectAsserter');
        $this->getAssertionManager()->setAlias('collection', 'CollectionAsserter');
        $this->getAssertionManager()->setAlias('list', 'ListAsserter');
        $this->getAssertionManager()->setAlias('set', 'SetAsserter');
        $this->getAssertionManager()->setAlias('hashmap', 'HashMapAsserter');
        $this->getAssertionManager()->setAlias('datasource', 'DataSourceAsserter');
    }
}
