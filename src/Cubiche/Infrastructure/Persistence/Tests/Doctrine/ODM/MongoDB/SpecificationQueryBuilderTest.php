<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Tests\Doctrine\ODM\MongoDB;

use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\Selector\Field;
use Cubiche\Domain\Specification\Selector\Selector;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\SpecificationQueryBuilder;
use Cubiche\Infrastructure\Persistence\Tests\Doctrine\ODM\MongoDB\Documents\Document;
use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * Specification Query Builder Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SpecificationQueryBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function testVisitValue()
    {
        $this->notSupportedExceptionTest(new Value(5));
    }

    /**
     * @test
     */
    public function testVisitKey()
    {
        $this->visitFieldTest(Criteria::key('intValue'));
    }

    /**
     * @test
     */
    public function testVisitMethod()
    {
        $this->visitFieldTest(Criteria::method('intValue'));
    }

    /**
     * @test
     */
    public function testVisitProperty()
    {
        $this->visitFieldTest(Criteria::property('intValue'));
    }

    /**
     * @test
     */
    public function testVisitThis()
    {
        $this->notSupportedExceptionTest(Criteria::this());
    }

    /**
     * @test
     */
    public function testVisitCustom()
    {
        $this->notSupportedExceptionTest(Criteria::custom(function () {

        }));
    }

    /**
     * @test
     */
    public function testVisitComposite()
    {
        $this->visitFieldSelectorTest(Criteria::property('embedded')->property('intValue'), 'embedded.intValue');
    }

    /**
     * @test
     */
    public function testVisitGreaterThan()
    {
        $queryBuilder = $this->queryBuilder(Criteria::property('intValue')->gt(5));
        $expected = new Builder($this->dm, Document::class);
        $expected->field('intValue')->gt(5);

        $this->assertEquals($expected->getQueryArray(), $queryBuilder->getQueryArray());
    }

    /**
     * @param SpecificationInterface $specification
     */
    protected function notSupportedExceptionTest(SpecificationInterface $specification)
    {
        $this->setExpectedException(
            \LogicException::class,
            \sprintf('The %s specification is not supported by Doctrine\ODM\MongoDB', \get_class($specification))
        );

        $this->queryBuilder($specification);
    }

    /**
     * @param Field $field
     */
    protected function visitFieldTest(Field $field)
    {
        return $this->visitFieldSelectorTest($field, $field->name());
    }

    /**
     * @param Selector $field
     * @param string   $expectedName
     */
    protected function visitFieldSelectorTest(Selector $field, $expectedName)
    {
        $queryBuilder = $this->queryBuilder($field);

        $expected = new Builder($this->dm, Document::class);
        $expected->field($expectedName)->equals(true);

        $this->assertEquals($expected->getQueryArray(), $queryBuilder->getQueryArray());
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\SpecificationQueryBuilder
     */
    protected function queryBuilder(SpecificationInterface $specification)
    {
        return new SpecificationQueryBuilder($this->dm, Document::class, $specification);
    }
}
