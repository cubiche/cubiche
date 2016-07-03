<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification\Tests\Units\Constraint;

/**
 * Less Than Equal Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LessThanEqualTests extends BinaryConstraintOperatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function evaluateSuccessDataProvider()
    {
        return array(
            array(4),
            array(5),
            array(5.0),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateFailureDataProvider()
    {
        return array(
            array(6),
        );
    }
}
