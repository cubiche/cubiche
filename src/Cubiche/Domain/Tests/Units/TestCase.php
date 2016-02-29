<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Units;

use Closure;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\analyzer as Analyzer;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test as Test;
use mageekguy\atoum\test\assertion\manager as Manager;

abstract class TestCase extends Test
{
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

        $this->getAsserterGenerator()->addNamespace('Cubiche\Domain\Tests\Atoum\Asserters');
        $this->getAsserterGenerator()->addNamespace('Cubiche\Domain\Collections\Tests\Asserters');

        $this->getAssertionManager()->setAlias('getMock', 'MockBuilder');
        $this->getAssertionManager()->setAlias('collection', 'Collection');
    }

    /**
     * {@inheritdoc}
     */
    public function getTestedClassName()
    {
        $className = parent::getTestedClassName();

        return substr($className, 0, strrpos($className, 'Tests'));
    }
}
