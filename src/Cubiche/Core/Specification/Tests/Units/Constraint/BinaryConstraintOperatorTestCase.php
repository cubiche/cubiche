<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification\Tests\Units\Constraint;

use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Selector\This;
use Cubiche\Core\Selector\Value;
use Cubiche\Core\Specification\Constraint\BinaryConstraintOperator;
use Cubiche\Core\Specification\Tests\Units\SpecificationTestCase;

/**
 * Binary Constraint Operator Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class BinaryConstraintOperatorTestCase extends SpecificationTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Tests\TestCase::defaultConstructorArguments()
     */
    public function defaultConstructorArguments()
    {
        return array(new This(), new Value(5));
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($constraint = $this->newDefaultTestedInstance())
            ->then
                ->object($constraint)
                    ->isInstanceOf(BinaryConstraintOperator::class)
        ;
    }

    /**
     * Test left/right.
     */
    public function testLeftRight()
    {
        $this
            /* @var \Cubiche\Core\Specification\Constraint\BinaryConstraintOperator $constraint */
            ->given($constraint = $this->newDefaultTestedInstance())
            ->then()
                ->object($constraint->left())
                    ->isInstanceOf(SelectorInterface::class)
                ->object($constraint->right())
                    ->isInstanceOf(SelectorInterface::class)
        ;
    }
}
