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

use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\test as Test;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * Abstract Test Case Class.
 *
 * @method $this given()
 * @method $this if()
 * @method $this and()
 * @method $this then()
 * @method $this when()
 * @method $this assert()
 * @method $this dump()
 * @method $this stop()
 * @method $this executeOnFailure()
 * @method $this dumpOnFailure()
 * @method $this mockGenerator()
 * @method $this mockClass()
 * @method $this mockTestedClass()
 * @method $this newMockInstance()
 * @method $this callStaticOnTestedClass()
 * @method $this calling()
 * @method $this resetMock()
 * @method $this resetAdapter()
 * @method $this from()
 * @method $this use()
 * @method $this as()
 * @method $this method()
 * @method $this in()
 * @method \Cubiche\Domain\Tests\Asserters\ArrayAsserter array()
 * @method \Cubiche\Domain\Tests\Asserters\BooleanAsserter boolean()
 * @method \Cubiche\Domain\Tests\Asserters\CastToStringAsserter castToString()
 * @method \Cubiche\Domain\Tests\Asserters\ClassAsserter class()
 * @method \Cubiche\Domain\Tests\Asserters\DateIntervalAsserter dateInterval()
 * @method \Cubiche\Domain\Tests\Asserters\DateTimeAsserter dateTime()
 * @method \Cubiche\Domain\Tests\Asserters\ErrorAsserter error()
 * @method \Cubiche\Domain\Tests\Asserters\ExceptionAsserter exception()
 * @method \Cubiche\Domain\Tests\Asserters\FloatAsserter float()
 * @method \Cubiche\Domain\Tests\Asserters\HashAsserter hash()
 * @method \Cubiche\Domain\Tests\Asserters\IntegerAsserter integer()
 * @method \Cubiche\Domain\Tests\Asserters\MockAsserter mock()
 * @method \Cubiche\Domain\Tests\Asserters\MysqlDateTimeAsserter mysqlDateTime()
 * @method \Cubiche\Domain\Tests\Asserters\ObjectAsserter object()
 * @method \Cubiche\Domain\Tests\Asserters\OutputAsserter output()
 * @method \Cubiche\Domain\Tests\Asserters\SizeOfAsserter sizeOf()
 * @method \Cubiche\Domain\Tests\Asserters\StreamAsserter stream()
 * @method \Cubiche\Domain\Tests\Asserters\StringAsserter string()
 * @method \Cubiche\Domain\Tests\Asserters\Utf8StringAsserter utf8String()
 * @method \Cubiche\Domain\Tests\Asserters\VariableAsserter variable()
 * @method \Cubiche\Domain\Tests\Asserters\CollectionAsserter collection()
 * @method \Cubiche\Domain\Tests\Asserters\DataSourceAsserter datasource()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class TestCase extends Test
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

        $this->getAsserterGenerator()->addNamespace('Cubiche\Domain\Tests\Asserters');

        $this->getAssertionManager()->setAlias('array', 'ArrayAsserter');
        $this->getAssertionManager()->setAlias('boolean', 'BooleanAsserter');
        $this->getAssertionManager()->setAlias('castToString', 'CastToStringAsserter');
        $this->getAssertionManager()->setAlias('class', 'ClassAsserter');
        $this->getAssertionManager()->setAlias('dateInterval', 'DateIntervalAsserter');
        $this->getAssertionManager()->setAlias('dateTime', 'DateTimeAsserter');
        $this->getAssertionManager()->setAlias('error', 'ErrorAsserter');
        $this->getAssertionManager()->setAlias('exception', 'ExceptionAsserter');
        $this->getAssertionManager()->setAlias('float', 'FloatAsserter');
        $this->getAssertionManager()->setAlias('hash', 'HashAsserter');
        $this->getAssertionManager()->setAlias('integer', 'IntegerAsserter');
        $this->getAssertionManager()->setAlias('mock', 'MockAsserter');
        $this->getAssertionManager()->setAlias('mysqlDateTime', 'MysqlDateTimeAsserter');
        $this->getAssertionManager()->setAlias('object', 'ObjectAsserter');
        $this->getAssertionManager()->setAlias('output', 'OutputAsserter');
        $this->getAssertionManager()->setAlias('sizeOf', 'SizeOfAsserter');
        $this->getAssertionManager()->setAlias('stream', 'StreamAsserter');
        $this->getAssertionManager()->setAlias('string', 'StringAsserter');
        $this->getAssertionManager()->setAlias('utf8String', 'Utf8StringAsserter');
        $this->getAssertionManager()->setAlias('variable', 'VariableAsserter');
    }

    /**
     * {@inheritdoc}
     *
     * @see \mageekguy\atoum\test::getTestedClassName()
     */
    public function getTestedClassName()
    {
        $className = parent::getTestedClassName();

        return substr($className, 0, strrpos($className, 'Tests'));
    }
}
