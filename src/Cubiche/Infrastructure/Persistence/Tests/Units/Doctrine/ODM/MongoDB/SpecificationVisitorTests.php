<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Tests\Units\Doctrine\ODM\MongoDB;

use Cubiche\Domain\Persistence\Tests\Fixtures\User;
use Cubiche\Domain\Persistence\Tests\Fixtures\UserId;
use Cubiche\Domain\Specification\AndSpecification;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\NotSpecification;
use Cubiche\Domain\Specification\Selector\Composite;
use Cubiche\Domain\Specification\Selector\Custom;
use Cubiche\Domain\Specification\Selector\Field;
use Cubiche\Domain\Specification\Selector\Property;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder;
use Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\SpecificationVisitor;

/**
 * Specification Visitor Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SpecificationVisitorTests extends TestCase
{
    /**
     * Test visitAnd.
     */
    public function testVisitAnd()
    {
        $this->visitTest(
            Criteria::property('foo')->eq(10)->andX(Criteria::property('bar')->eq(20)),
            function () {
                return  $this->createQueryBuilder()
                    ->field('foo')->equals(10)
                    ->field('bar')->equals(20);
            }
        );

        $this->visitTest(
            Criteria::property('foo')->gt(10)->andX(Criteria::property('foo')->lt(20)),
            function () {
                return $this->createQueryBuilder()
                    ->field('foo')->gt(10)
                    ->field('foo')->lt(20);
            }
        );

        $this->visitTest(
            new AndSpecification(
                Criteria::property('foo')->lt(20)->orX(Criteria::property('bar')->lt(20)),
                Criteria::property('foo')->gt(10)->orX(Criteria::property('foo')->lt(25))
            ),
            function () {
                $qb = $this->createQueryBuilder();

                return $qb
                    ->addAnd($qb->expr()
                        ->addOr($qb->expr()->field('foo')->lt(20))
                        ->addOr($qb->expr()->field('bar')->lt(20)))
                    ->addAnd($qb->expr()
                        ->addOr($qb->expr()->field('foo')->gt(10))
                        ->addOr($qb->expr()->field('foo')->lt(25)));
            }
        );
    }

    /**
     * Test visitOr.
     */
    public function testVisitOr()
    {
        $this->visitTest(
            Criteria::property('foo')->eq(10)->orX(Criteria::property('bar')->eq(20)),
            function () {
                $qb = $this->createQueryBuilder();

                return $qb
                    ->addOr($qb->expr()->field('foo')->equals(10))
                    ->addOr($qb->expr()->field('bar')->equals(20));
            }
        );
    }

    /**
     * Test visitOr.
     */
    public function testVisitNot()
    {
        $this->visitTest(new NotSpecification(Criteria::property('foo')->eq(10)), function () {
            $qb = $this->createQueryBuilder();

            return $qb->not($qb->expr()->field('foo')->equals(10));
        });
    }

    /**
     * Test visitValue.
     */
    public function testVisitValue()
    {
        $this->notSupportedOperationTest(Criteria::false());
    }

    /**
     * Test visitValue.
     */
    public function testVisitKey()
    {
        $this->visitFieldTest(Criteria::key('foo'));
    }

    /**
     * Test visitProperty.
     */
    public function testVisitPorperty()
    {
        $this->visitFieldTest(Criteria::property('foo'));
    }

    /**
     * Test visitMethod.
     */
    public function testVisitMethod()
    {
        $this->notSupportedOperationTest(Criteria::method('foo'));
    }

    /**
     * Test visitCustom.
     */
    public function testVisitCustom()
    {
        $this->notSupportedOperationTest(Criteria::custom(function () {

        }));
    }

    /**
     * Test visitThis.
     */
    public function testVisitThis()
    {
        $this->notSupportedOperationTest(Criteria::this());
    }

    /**
     * Test visitComposite.
     */
    public function testVisitComposite()
    {
        $this->visitFieldTest(Criteria::property('foo')->property('bar'), 'foo.bar');
        $this->visitFieldTest(Criteria::property('foo')->property('bar')->key('length'), 'foo.bar.length');
    }

    /**
     * Test visitCount.
     */
    public function testVisitCount()
    {
        $this->notSupportedOperationTest(Criteria::count());
    }

    /**
     * Test visitGreaterThan.
     */
    public function tesVisittGreaterThan()
    {
        $this->visitTest(Criteria::property('foo')->gt(10), function () {
            return $this->createQueryBuilder()->field('foo')->gt(10);
        });
    }

    /**
     * Test visitGreaterThanEqual.
     */
    public function testVisitGreaterThanEqual()
    {
        $this->visitTest(Criteria::property('foo')->gte(10), function () {
            return $this->createQueryBuilder()->field('foo')->gte(10);
        });
    }

    /**
     * Test visitLessThan.
     */
    public function testVisitLessThan()
    {
        $this->visitTest(Criteria::property('foo')->lt(10), function () {
            return $this->createQueryBuilder()->field('foo')->lt(10);
        });
    }

    /**
     * Test visitLessThanEqual.
     */
    public function testVisitLessThanEqual()
    {
        $this->visitTest(Criteria::property('foo')->lte(10), function () {
            return $this->createQueryBuilder()->field('foo')->lte(10);
        });
    }

    /**
     * Test visitEqual.
     */
    public function testVisitEqual()
    {
        $this->visitTest(Criteria::property('foo')->eq(10), function () {
            return $this->createQueryBuilder()->field('foo')->equals(10);
        });

        $this->visitTest(Criteria::property('foo')->count()->eq(10), function () {
            return $this->createQueryBuilder()->field('foo')->size(10);
        });

        $user = new User(UserId::next(), 'user', 20);

        $this->visitTest(Criteria::eq($user), function () use ($user) {
            return $this->createQueryBuilder()->field('id')->equals($user->id()->toNative());
        });

        $this->visitTest(Criteria::property('id')->eq($user->id()), function () use ($user) {
            return $this->createQueryBuilder()->field('id')->equals($user->id()->toNative());
        });

        $this->logicExceptionTest(
            Criteria::custom(function () {

            })->eq(10),
            \sprintf('The %s specification cannot be used in the field name', Custom::class)
        );

        $this->logicExceptionTest(
            Criteria::property('foo')->eq(Criteria::property('bar')),
            \sprintf('The %s specification cannot be used as a value specification', Property::class)
        );
    }

    /**
     * Test visitNotEqual.
     */
    public function testVisitNotEqual()
    {
        $this->visitTest(Criteria::property('foo')->neq(10), function () {
            return $this->createQueryBuilder()->field('foo')->notEqual(10);
        });
    }

    /**
     * Test visitSame.
     */
    public function testVisitSame()
    {
        $this->visitTest(Criteria::property('foo')->same(10), function () {
            return $this->createQueryBuilder()->field('foo')->equals(10);
        });
    }

    /**
     * Test visitNotSame.
     */
    public function testVisitNotSame()
    {
        $this->visitTest(Criteria::property('foo')->notsame(10), function () {
            return $this->createQueryBuilder()->field('foo')->notEqual(10);
        });
    }

    /**
     * Test visitAll.
     */
    public function testVisitAll()
    {
        $this->visitTest(Criteria::property('foo')->all(Criteria::property('bar')->gt(10)), function () {
            $qb = $this->createQueryBuilder();

            return $qb->field('foo')->all(
                $qb->expr()->elemMatch(
                    $qb->expr()->field('bar')->gt(10)
                )->getQuery()
            );
        });
    }

    /**
     * Test visitAtLeast.
     */
    public function testAtLeast()
    {
        $this->visitTest(Criteria::property('foo')->any(Criteria::property('bar')->gt(10)), function () {
            $qb = $this->createQueryBuilder();

            return $qb->field('foo')
                ->elemMatch(
                    $qb->expr()->field('bar')->gt(10)
                );
        });

        $this->notSupportedOperationTest(Criteria::property('foo')->atLeast(2, Criteria::property('bar')->gt(10)));
    }

    /**
     * @param SpecificationInterface $criteria
     * @param \Closure               $expectedCreate
     */
    protected function visitTest(SpecificationInterface $criteria, \Closure $expectedCreate)
    {
        $this
            ->given($qb = $this->createQueryBuilder())
            ->given($visitor = $this->createVisitor($qb))
            ->given($criteria)
            ->given($expected = $expectedCreate())
            ->when($criteria->accept($visitor))
                ->array($qb->getQueryArray())
                    ->isEqualTo($expected->getQueryArray())
            ;
    }

    /**
     * @param Field|Composite $field
     * @param string          $expected
     */
    protected function visitFieldTest($field, $expected = null)
    {
        $expected = $expected === null && $field instanceof Field ? $field->name() : $expected;
        $this->visitTest($field, function () use ($expected) {
            return $this->createQueryBuilder()->field($expected)->equals(true);
        });
    }

    /**
     * @param SpecificationInterface $specification
     */
    protected function notSupportedOperationTest(SpecificationInterface $specification)
    {
        $this->logicExceptionTest(
            $specification,
            \sprintf('The %s operation is not supported by Doctrine\ODM\MongoDB', \get_class($specification))
        );
    }

    /**
     * @param SpecificationInterface $specification
     * @param string                 $message
     */
    private function logicExceptionTest(SpecificationInterface $specification, $message)
    {
        $this
            ->given($qb = $this->createQueryBuilder())
            ->given($visitor = $this->createVisitor($qb))
            ->exception(function () use ($visitor, $specification) {
                $specification->accept($visitor);
            })
                ->isInstanceOf(\LogicException::class)
                    ->hasMessage($message)
            ;
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\SpecificationVisitor
     */
    protected function createVisitor(QueryBuilder $queryBuilder)
    {
        return new SpecificationVisitor($queryBuilder);
    }

    /**
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function createQueryBuilder()
    {
        return new QueryBuilder($this->dm(), User::class);
    }
}
