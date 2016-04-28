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
 * Less Than Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class LessThanTests extends BinaryConstraintOperatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitLessThan';
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateSuccessDataProvider()
    {
        return array(
            array(4),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateFailureDataProvider()
    {
        return array(
            array(6),
            array(5),
            array(5.0),
        );
    }
}
