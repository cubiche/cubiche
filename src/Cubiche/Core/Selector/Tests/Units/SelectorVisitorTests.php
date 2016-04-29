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
use Cubiche\Core\Visitor\Tests\Units\VisitorTestCase;

/**
 * Selector Visitor Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorVisitorTests extends VisitorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function visiteeInterface()
    {
        return SelectorInterface::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'acceptSelectorVisitor';
    }
}
