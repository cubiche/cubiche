<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification\Tests\Units\Quantifier;

use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Specification\Quantifier\Quantifier;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Specification\Tests\Units\SpecificationTestCase;

/**
 * Quantifier Test Case Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class QuantifierTestCase extends SpecificationTestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->isSubClassOf(Quantifier::class)
        ;
    }

    /**
     * Test selector.
     */
    public function testSelectorSpecification()
    {
        $this
            /* @var \Cubiche\Core\Specification\Quantifier\Quantifier $quantifier */
            ->given($quantifier = $this->newDefaultTestedInstance())
            ->then()
                ->object($quantifier->selector())
                    ->isInstanceOf(SelectorInterface::class)
                ->object($quantifier->specification())
                    ->isInstanceOf(SpecificationInterface::class)
        ;
    }
}
