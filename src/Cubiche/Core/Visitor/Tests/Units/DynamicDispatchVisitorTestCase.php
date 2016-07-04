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
 * Dynamic Dispatch Visitor Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class DynamicDispatchVisitorTestCase extends DynamicDispatchVisitorInterfaceTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function canHandlerVisiteeDataProvider()
    {
        return array(
            array($this->newDefaultTestedInstance(), $this->newMockInstance(VisiteeInterface::class), false),
        );
    }
}
