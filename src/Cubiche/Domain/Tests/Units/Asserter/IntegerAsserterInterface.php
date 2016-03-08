<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Units\Asserter;

/**
 * Integer Asserter Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface IntegerAsserterInterface extends VariableAsserterInterface
{
    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isZero($failMessage = null);

    /**
     * @param int         $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isGreaterThan($value, $failMessage = null);

    /**
     * @param int         $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isGreaterThanOrEqualTo($value, $failMessage = null);

    /**
     * @param int         $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isLessThan($value, $failMessage = null);

    /**
     * @param int         $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isLessThanOrEqualTo($value, $failMessage = null);
}
