<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable\Tests\Units;

use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Visitor\Tests\Units\VisitorTestCase;

/**
 * Comparator Visitor Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ComparatorVisitorTests extends VisitorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisitorInterfaceTestCase::visiteeInterface()
     */
    protected function visiteeInterface()
    {
        return ComparatorInterface::class;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisitorInterfaceTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'acceptComparatorVisitor';
    }
}
