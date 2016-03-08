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
 * Object Asserter Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ObjectAsserterInterface extends VariableAsserterInterface
{
    /**
     * @param object      $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isInstanceOf($value, $failMessage = null);

    /**
     * @param object      $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isNotInstanceOf($value, $failMessage = null);

    /**
     * @param int         $size
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasSize($size, $failMessage = null);

    /**
     * @param object      $object
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isCloneOf($object, $failMessage = null);

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isEmpty($failMessage = null);

    public function toString();

    public function toArray();
}
