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
use Cubiche\Domain\Collections\Tests\Asserters\CollectionAsserter;
use Cubiche\Domain\Collections\Tests\Asserters\DataSourceAsserter;
use Cubiche\Domain\Equatable\Tests\Asserters\VariableAsserter;
use Cubiche\Domain\Tests\Asserters\ArrayAsserter;
use Cubiche\Domain\Tests\Asserters\BooleanAsserter;
use Cubiche\Domain\Tests\Asserters\CastToStringAsserter;
use Cubiche\Domain\Tests\Asserters\ClassAsserter;
use Cubiche\Domain\Tests\Asserters\DateIntervalAsserter;
use Cubiche\Domain\Tests\Asserters\DateTimeAsserter;
use Cubiche\Domain\Tests\Asserters\ErrorAsserter;
use Cubiche\Domain\Tests\Asserters\ExceptionAsserter;
use Cubiche\Domain\Tests\Asserters\FloatAsserter;
use Cubiche\Domain\Tests\Asserters\HashAsserter;
use Cubiche\Domain\Tests\Asserters\IntegerAsserter;
use Cubiche\Domain\Tests\Asserters\MockAsserter;
use Cubiche\Domain\Tests\Asserters\MysqlDateTimeAsserter;
use Cubiche\Domain\Tests\Asserters\ObjectAsserter;
use Cubiche\Domain\Tests\Asserters\OutputAsserter;
use Cubiche\Domain\Tests\Asserters\SizeOfAsserter;
use Cubiche\Domain\Tests\Asserters\StreamAsserter;
use Cubiche\Domain\Tests\Asserters\StringAsserter;
use Cubiche\Domain\Tests\Asserters\Utf8StringAsserter;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\analyzer as Analyzer;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test as Test;
use mageekguy\atoum\test\assertion\manager as Manager;

/**
 * TestCase class.
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
 * @method ArrayAsserter array()
 * @method BooleanAsserter boolean()
 * @method CastToStringAsserter castToString()
 * @method ClassAsserter class()
 * @method DateIntervalAsserter dateInterval()
 * @method DateTimeAsserter dateTime()
 * @method ErrorAsserter error()
 * @method ExceptionAsserter exception()
 * @method FloatAsserter float()
 * @method HashAsserter hash()
 * @method IntegerAsserter integer()
 * @method MockAsserter mock()
 * @method MysqlDateTimeAsserter mysqlDateTime()
 * @method ObjectAsserter object()
 * @method OutputAsserter output()
 * @method SizeOfAsserter sizeOf()
 * @method StreamAsserter stream()
 * @method StringAsserter string()
 * @method Utf8StringAsserter utf8String()
 * @method VariableAsserter variable()
 * @method CollectionAsserter collection()
 * @method DataSourceAsserter datasource()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
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

        $this->getAsserterGenerator()->addNamespace('Cubiche\Domain\Tests\Asserters');
        $this->getAsserterGenerator()->addNamespace('Cubiche\Domain\Collections\Tests\Asserters');
        $this->getAsserterGenerator()->addNamespace('Cubiche\Domain\Equatable\Tests\Asserters');

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
        $this->getAssertionManager()->setAlias('collection', 'CollectionAsserter');
        $this->getAssertionManager()->setAlias('datasource', 'DataSourceAsserter');
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
