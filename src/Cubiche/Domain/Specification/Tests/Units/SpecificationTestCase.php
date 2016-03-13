<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units;

use Cubiche\Domain\Specification\AndSpecification;
use Cubiche\Domain\Specification\NotSpecification;
use Cubiche\Domain\Specification\OrSpecification;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Tests\Units\TestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;

/**
 * SpecificationTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class SpecificationTestCase extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        \Closure $reflectionClassFactory = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );

        $this->getAssertionManager()
            ->setHandler(
                'randomSpecification',
                function () {
                    return $this->randomSpecification();
                }
            )
            ->setHandler(
                'shouldVisitMethod',
                function () {
                    return $this->shouldVisitMethod();
                }
            )
        ;
    }

    /**
     * @return SpecificationInterface
     */
    abstract protected function randomSpecification();

    /**
     * @return string
     */
    abstract protected function shouldVisitMethod();

    /*
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then
                ->object($specification)
                    ->isInstanceOf(SpecificationInterface::class)
        ;
    }

    /*
     * Test andX.
     */
    public function testAndX()
    {
        $this
            ->given(
                $specification = $this->randomSpecification(),
                $other = $this->randomSpecification()
            )
            ->then
            ->when($andSpecification = $specification->andX($other))
                ->object($andSpecification)
                    ->isInstanceOf(AndSpecification::class)
                ->object($andSpecification->left())
                    ->isIdenticalTo($specification)
                ->object($andSpecification->right())
                    ->isIdenticalTo($other)
        ;
    }

    /*
     * Test orX.
     */
    public function testOrX()
    {
        $this
            ->given(
                $specification = $this->randomSpecification(),
                $other = $this->randomSpecification()
            )
            ->then
            ->when($orSpecification = $specification->orX($other))
                ->object($orSpecification)
                    ->isInstanceOf(OrSpecification::class)
                ->object($orSpecification->left())
                    ->isIdenticalTo($specification)
                ->object($orSpecification->right())
                    ->isIdenticalTo($other)
        ;
    }

    /*
     * Test not.
     */
    public function testNot()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then
            ->when($notSpecification = $specification->not($specification))
                ->object($notSpecification)
                    ->isInstanceOf(NotSpecification::class)
                ->object($notSpecification->specification())
                    ->isIdenticalTo($specification)
        ;
    }

    /*
     * Test visit.
     */
    public function testVisit()
    {
        $shouldVisitMethod = $this->shouldVisitMethod();

        $this
            ->given($visitorMock = new \mock\Cubiche\Domain\Specification\SpecificationVisitorInterface())
            ->calling($visitorMock)
                ->methods(
                    function ($method) use ($shouldVisitMethod) {
                        return $method === strtolower($shouldVisitMethod);
                    }
                )
                ->return = 25
        ;

        $this
            ->given($specification = $this->randomSpecification())
            ->when($result = $specification->accept($visitorMock))
                ->mock($visitorMock)
                    ->call($shouldVisitMethod)
                        ->withArguments($specification)
                        ->once()
                ->integer($result)
                    ->isEqualTo(25)
        ;
    }
}
