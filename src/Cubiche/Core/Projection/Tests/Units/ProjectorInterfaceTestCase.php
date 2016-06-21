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

use Cubiche\Core\Projection\ProjectorInterface;

/**
 * Projector Interface Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ProjectorInterfaceTestCase extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->implements(ProjectorInterface::class)
        ;
    }

    /**
     * Test project.
     *
     * @param ProjectorInterface $projector
     * @param mixed              $value
     * @param \Iterator          $expected
     *
     * @dataProvider projectDataProvider
     */
    public function testProject(ProjectorInterface $projector, $value, \Iterator $expected)
    {
        $this
            ->given($projector, $value)
            ->when($result = $projector->project($value))
            ->then($this->equalsProjections($result, $expected));
    }

    /**
     * @param \Iterator $result
     * @param \Iterator $expected
     */
    protected function equalsProjections(\Iterator $result, \Iterator $expected)
    {
        $result->rewind();
        $expected->rewind();
        while ($result->valid() && $expected->valid()) {
            $this
                ->given(
                    /** @var \Cubiche\Core\Projection\ProjectionBuilderInterface $resultValue */
                    $resultValue = $result->current(),
                    /** @var \Cubiche\Core\Projection\ProjectionBuilderInterface $expectedValue */
                    $expectedValue = $expected->current()
                )
                ->then()
                    ->object($resultValue->projection())->isEqualTo($expectedValue->projection())
            ;
            $result->next();
            $expected->next();
        }

        $this
            ->then()
                ->boolean($result->valid())
                    ->isFalse()
                ->boolean($expected->valid())
                    ->isFalse()
        ;
    }

    /**
     * @return array
     */
    abstract protected function projectDataProvider();
}
