<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Validator;

use Cubiche\Core\Validator\Exception\ValidationException;

/**
 * Validator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ValidatorInterface
{
    /**
     * Validates a value against a rule or a list of rules.
     *
     * @param mixed  $value
     * @param mixed  $constraints
     * @param string $group
     *
     * @return bool
     */
    public static function validate($value, $constraints = null, $group = null);

    /**
     * Validates a value against a rule or a list of rules.
     *
     * @param mixed  $value
     * @param mixed  $constraints
     * @param string $group
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public static function assert($value, $constraints = null, $group = null);
}
