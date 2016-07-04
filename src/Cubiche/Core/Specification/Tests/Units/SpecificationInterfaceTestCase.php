<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification\Tests\Units;

use Cubiche\Core\Specification\AndSpecification;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\OrSpecification;
use Cubiche\Core\Specification\Specification;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase;

/**
 * Specification Interface Test Case class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SpecificationInterfaceTestCase extends VisiteeInterfaceTestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(SpecificationInterface::class)
        ;
    }

    /**
     * Test andX.
     */
    public function testAndX()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Specification\SpecificationInterface $specification */
                $specification = $this->newDefaultTestedInstance(),
                $other = $this->newRandomSpecification()
            )
            ->then()
            ->when($andSpecification = $specification->andX($other))
                ->object($andSpecification)
                    ->isInstanceOf(AndSpecification::class)
                ->object($andSpecification->left())
                    ->isIdenticalTo($specification)
                ->object($andSpecification->right())
                    ->isIdenticalTo($other)
        ;
    }

    /**
     * Test orX.
     */
    public function testOrX()
    {
        $this
            ->given(
                /** @var \Cubiche\Core\Specification\SpecificationInterface $specification */
                $specification = $this->newDefaultTestedInstance(),
                $other = $this->newRandomSpecification()
            )
            ->then()
            ->when($orSpecification = $specification->orX($other))
                ->object($orSpecification)
                    ->isInstanceOf(OrSpecification::class)
                ->object($orSpecification->left())
                    ->isIdenticalTo($specification)
                ->object($orSpecification->right())
                    ->isIdenticalTo($other)
        ;
    }

    /**
     * Test magic and/or.
     */
    public function testMagicCall()
    {
        $this
            /* @var \Cubiche\Core\Specification\SpecificationInterface $specificationMock */
            ->given($specificationMock = $this->newDefaultMockTestedInstance())
            ->given($specification = $this->newRandomSpecification())
            ->when($specificationMock->and($specification))
                ->mock($specificationMock)
                    ->call('andX')
                        ->withArguments($specification)->once()
            ->when($specificationMock->or($specification))
                ->mock($specificationMock)
                    ->call('orX')
                        ->withArguments($specification)->once()
            ;
    }

    /**
     * Test not.
     */
    public function testNot()
    {
        $this
            /* @var \Cubiche\Core\Specification\SpecificationInterface $specification */
            ->given($specification = $this->newDefaultTestedInstance())
            ->then()
            ->when($notSpecification = $specification->not())
                ->object($notSpecification)
                    ->isInstanceOf(SpecificationInterface::class)
                ->object($notSpecification->not())
                    ->isEqualTo($specification)
        ;
    }

    /**
     * Test evaluate success.
     *
     * @dataProvider evaluateSuccessDataProvider
     */
    public function testEvaluateSuccess($value)
    {
        $this
            /* @var \Cubiche\Core\Specification\SpecificationInterface $specification */
            ->given($specification = $this->newDefaultTestedInstance())
            ->then()
                ->boolean($specification->evaluate($value))
                    ->isTrue()
                ->boolean($specification->not()->evaluate($value))
                    ->isFalse()
        ;
    }

    /**
     * Test evaluate failure.
     *
     * @dataProvider evaluateFailureDataProvider
     */
    public function testEvaluateFailure($value)
    {
        $this
            /* @var \Cubiche\Core\Specification\SpecificationInterface $specification */
            ->given($specification = $this->newDefaultTestedInstance())
            ->then()
                ->boolean($specification->evaluate($value))
                    ->isFalse()
                ->boolean($specification->not()->evaluate($value))
                    ->isTrue()
        ;
    }

    /**
     * @return array
     */
    abstract protected function evaluateSuccessDataProvider();

    /**
     * @return array
     */
    abstract protected function evaluateFailureDataProvider();

    /**
     * @return \Cubiche\Core\Specification\SpecificationInterface
     */
    protected function newRandomSpecification()
    {
        switch (\rand(0, 3)) {
            case 0:
                return Criteria::eq(5);
            case 1:
                return Criteria::gte(5);
            case 2:
                return Criteria::property('foo')->lte(10);
            case 3:
            default:
                return Criteria::false();
        }
    }
}
