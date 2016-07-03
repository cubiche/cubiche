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

use Cubiche\Core\Specification\Specification;

/**
 * Specification Test Case class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SpecificationTestCase extends SpecificationInterfaceTestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->isSubClassOf(Specification::class)
        ;
    }

    /**
     * Test __call.
     */
    public function testMagicCall()
    {
        parent::testMagicCall();

        $this
            ->given($specificationMock = $this->newDefaultMockTestedInstance())
            ->given($specification = $this->newRandomSpecification())
            ->exception(function () use ($specificationMock, $specification) {
                $specificationMock->foo($specification);
            })
                ->isInstanceOf(\BadMethodCallException::class)
            ;
    }
}
