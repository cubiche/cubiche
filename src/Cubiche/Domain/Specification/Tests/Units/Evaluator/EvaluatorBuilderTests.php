<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Specification\Tests\Units\Evaluator;

use Cubiche\Domain\Specification\AndSpecification;
use Cubiche\Domain\Specification\Criteria;
use Cubiche\Domain\Specification\Evaluator\Evaluator;
use Cubiche\Domain\Specification\Evaluator\EvaluatorBuilder;
use Cubiche\Domain\Specification\NotSpecification;
use Cubiche\Domain\Specification\OrSpecification;
use Cubiche\Domain\Specification\Selector\Count;
use Cubiche\Domain\Specification\Selector\Custom;
use Cubiche\Domain\Specification\Selector\Key;
use Cubiche\Domain\Specification\Selector\Method;
use Cubiche\Domain\Specification\Selector\Property;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Tests\Units\TestCase;

/**
 * EvaluatorBuilderTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EvaluatorBuilderTests extends TestCase
{
    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->then()
                ->object($evaluatorBuilder)
                    ->isInstanceOf(EvaluatorBuilder::class)
        ;
    }

    /*
     * Test evaluator.
     */
    public function testEvaluator()
    {
        $this
            ->given($specificationMock = new \mock\Cubiche\Domain\Specification\SpecificationInterface())
            ->calling($specificationMock)
            ->methods(
                function ($method) {
                    return $method === 'accept';
                }
            )
            ->return = 25
        ;

        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->when($result = $evaluatorBuilder->evaluator($specificationMock))
            ->then()
                ->mock($specificationMock)
                    ->call('accept')
                        ->withArguments($evaluatorBuilder)
                        ->once()
                ->integer($result)
                    ->isEqualTo(25)
        ;
    }

    /*
     * Test visitAnd.
     */
    public function testVisitAnd()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new AndSpecification(Criteria::gt(25), Criteria::lte(30)))
            ->when($resultVisit = $evaluatorBuilder->visitAnd($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
                ->boolean($resultVisit->evaluate(25))
                    ->isFalse()
                ->boolean($resultVisit->evaluate(12))
                    ->isFalse()
                ->boolean($resultVisit->evaluate(26))
                    ->isTrue()
                ->boolean($resultVisit->evaluate(30))
                    ->isTrue()
        ;
    }

    /*
     * Test visitOr.
     */
    public function testVisitOr()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new OrSpecification(Criteria::isFalse(), Criteria::same(5)))
            ->when($resultVisit = $evaluatorBuilder->visitOr($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
                ->boolean($resultVisit->evaluate(true))
                    ->isFalse()
                ->boolean($resultVisit->evaluate(5.0))
                    ->isFalse()
                ->boolean($resultVisit->evaluate(false))
                    ->isTrue()
                ->boolean($resultVisit->evaluate(5))
                    ->isTrue()
        ;
    }

    /*
     * Test visitNot.
     */
    public function testVisitNot()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new NotSpecification(Criteria::eq(25)))
            ->when($resultVisit = $evaluatorBuilder->visitNot($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
                ->boolean($resultVisit->evaluate(25))
                    ->isFalse()
                ->boolean($resultVisit->evaluate(16))
                    ->isTrue()
        ;
    }

    /*
     * Test visitValue.
     */
    public function testVisitValue()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new Value(true))
            ->when($resultVisit = $evaluatorBuilder->visitValue($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
                ->boolean($resultVisit->evaluate(null))
                    ->isTrue()
        ;
    }

    /*
     * Test visitKey.
     */
    public function testVisitKey()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new Key('foo'))
            ->when($resultVisit = $evaluatorBuilder->visitKey($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
                ->boolean($resultVisit->evaluate(array('foo' => true)))
                    ->isTrue()
        ;
    }

    /*
     * Test visitProperty.
     */
    public function testVisitProperty()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new Property('foo'))
            ->when($resultVisit = $evaluatorBuilder->visitProperty($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
                ->boolean($resultVisit->evaluate((object) array('foo' => true)))
                    ->isTrue()
        ;
    }

    /*
     * Test visitMethod.
     */
    public function testVisitMethod()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new Method('foo'))
            ->when($resultVisit = $evaluatorBuilder->visitMethod($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
        ;
    }

    /*
     * Test visitThis.
     */
    public function testVisitThis()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new This())
            ->when($resultVisit = $evaluatorBuilder->visitThis($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
        ;
    }

    /*
     * Test visitCustom.
     */
    public function testVisitCustom()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new Custom(function ($value) {
                return !$value;
            }))
            ->when($resultVisit = $evaluatorBuilder->visitCustom($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
        ;
    }

    /*
     * Test visitCount.
     */
    public function testVisitCount()
    {
        $this
            ->given($evaluatorBuilder = new EvaluatorBuilder())
            ->and($specification = new Count())
            ->when($resultVisit = $evaluatorBuilder->visitCount($specification))
            ->then()
                ->object($resultVisit)
                    ->isInstanceOf(Evaluator::class)
        ;
    }
}
