<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection\Tests\Units;

use Cubiche\Core\Projection\ProjectionBuilderInterface;

/**
 * Projection Builder Interface Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ProjectionBuilderInterfaceTestCase extends ProjectionWrapperInterfaceTestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
            ->implements(ProjectionBuilderInterface::class)
        ;
    }
}
