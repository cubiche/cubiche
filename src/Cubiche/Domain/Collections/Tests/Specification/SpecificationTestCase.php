<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Specification;

use Cubiche\Domain\Collections\Specification\SpecificationInterface;
use Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface;

/**
 * Specification Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SpecificationTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param SpecificationInterface $specification
     * @param string                 $method
     */
    protected function visitTest(SpecificationInterface $specification, $method)
    {
        $visitorMock = $this->getMock(SpecificationVisitorInterface::class);
        $visitorMock
            ->expects($this->once())
            ->method($method)
            ->with($this->identicalTo($specification))
            ->willReturn(25);

        $this->assertEquals(25, $specification->visit($visitorMock));
    }
}
