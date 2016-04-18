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

use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Visitor\Tests\Units\VisitorTestCase;

/**
 * Specification Visitor Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SpecificationVisitorTests extends VisitorTestCase
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisitorInterfaceTestCase::visiteeInterface()
     */
    protected function visiteeInterface()
    {
        return SpecificationInterface::class;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisitorInterfaceTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'acceptSpecificationVisitor';
    }
}
