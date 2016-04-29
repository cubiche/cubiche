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

use Cubiche\Core\Specification\Criteria;

/**
 * And Specification Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class AndSpecificationTests extends SpecificationTestCase
{
    /**
     * {@inheritdoc}
     */
    public function defaultConstructorArguments()
    {
        return array(Criteria::gt(25), Criteria::lte(30));
    }

    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitAnd';
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateSuccessDataProvider()
    {
        return array(
            array(26),
            array(30),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateFailureDataProvider()
    {
        return array(
            array(20),
            array(25),
            array(31),
        );
    }
}
