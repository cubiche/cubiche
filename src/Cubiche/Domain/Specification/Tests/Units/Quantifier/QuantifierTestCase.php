<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units\Quantifier;

use Cubiche\Domain\Specification\Quantifier\Quantifier;
use Cubiche\Domain\Specification\SelectorInterface;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Specification\Tests\Units\SpecificationTestCase;

/**
 * QuantifierTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class QuantifierTestCase extends SpecificationTestCase
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
                    ->isInstanceOf(Quantifier::class)
        ;
    }

    /*
     * Test selector.
     */
    public function testSelector()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
                ->object($specification->selector())
                    ->isInstanceOf(SelectorInterface::class)
        ;
    }

    /*
     * Test specification.
     */
    public function testSpecification()
    {
        $this
            ->given($specification = $this->randomSpecification())
            ->then()
                ->object($specification->specification())
                    ->isInstanceOf(SpecificationInterface::class)
        ;
    }
}
