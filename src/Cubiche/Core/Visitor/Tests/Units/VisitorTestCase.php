<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor\Tests\Units;

use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Visitor Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class VisitorTestCase extends VisitorInterfaceTestCase
{
    /**
     * Test visit.
     */
    public function testVisitVisiteeInterface()
    {
        $this
            /* @var \Cubiche\Core\Visitor\VisitorInterface $visitor */
            ->given($visitor = $this->newDefaultTestedInstance())
            ->given($visiteeMock = $this->newMockInstance(VisiteeInterface::class))
            ->exception(function () use ($visitor, $visiteeMock) {
                $visitor->visit($visiteeMock);
            })
                ->isInstanceOf(\LogicException::class)
            ;
    }
}
