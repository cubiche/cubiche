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

use Cubiche\Core\Visitor\Tests\Fixtures\Value;

/**
 * Null Visitor Tests Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NullVisitorTests extends LinkedVisitorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function visitDataProvider()
    {
        return array();
    }
    
    /**
     * {@inheritdoc}
     */
    protected function visitNextDataProvider()
    {
        return array();
    }
    
    /**
     * {@inheritdoc}
     */
    protected function canHandlerVisiteeDataProvider()
    {
        $data = parent::canHandlerVisiteeDataProvider();
    
        return \array_merge($data, array(
            array($this->newDefaultTestedInstance(), new Value(1), false),
        ));
    }
}
