<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units\Constraint;

use Cubiche\Domain\Specification\Constraint\BinarySelectorOperator;
use Cubiche\Domain\Specification\SelectorInterface;
use Cubiche\Domain\Specification\Tests\Units\SpecificationTestCase;

/**
 * BinarySelectorOperatorTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class BinarySelectorOperatorTestCase extends SpecificationTestCase
{
    /*
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($specification = $this->randomSpecification())
            ->then
                ->object($specification)
                    ->isInstanceOf(BinarySelectorOperator::class)
        ;
    }

    /*
     * Test left.
     */
    public function testLeft()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
                ->object($specification->left())
                    ->isInstanceOf(SelectorInterface::class)
        ;
    }

    /*
     * Test right.
     */
    public function testRight()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
                ->object($specification->right())
                    ->isInstanceOf(SelectorInterface::class)
        ;
    }
}
