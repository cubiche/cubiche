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
 * Variable Asserter Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface VariableAsserterInterface extends AsserterInterface
{
    /**
     * @param mixed       $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isEqualTo($value, $failMessage = null);

    /**
     * @param mixed       $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNotEqualTo($value, $failMessage = null);

    /**
     * @param mixed       $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isIdenticalTo($value, $failMessage = null);

    /**
     * @param mixed       $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNotIdenticalTo($value, $failMessage = null);

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNull($failMessage = null);

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNotNull($failMessage = null);

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isReferenceTo(&$reference, $failMessage = null);

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNotFalse($failMessage = null);

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNotTrue($failMessage = null);

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isCallable($failMessage = null);

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNotCallable($failMessage = null);
}
