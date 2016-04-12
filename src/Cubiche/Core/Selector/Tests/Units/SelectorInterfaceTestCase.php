<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector\Tests\Units;

use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Selector\SelectorVisitorInterface;
use Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase;

/**
 * Selector Interface Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class SelectorInterfaceTestCase extends VisiteeInterfaceTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase::testCreate()
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($selector = $this->newDefaultTestedInstance())
            ->then()
                ->object($selector)
                    ->isInstanceOf(SelectorInterface::class)
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase::visitorInterface()
     */
    protected function visitorInterface()
    {
        return SelectorVisitorInterface::class;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase::acceptActualVisitorMethod()
     */
    protected function acceptActualVisitorMethod()
    {
        return 'acceptSelectorVisitor';
    }
}
