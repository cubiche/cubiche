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
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value);

    /**
     * Validates a value against a rule or a list of rules.
     *
     * @param mixed $value
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function assert($value);

    /**
     * Get the constraints list.
     *
     * @return Assert
     */
    public function constraints();

    /**
     * Add an assert to the constraints list.
     *
     * @param Assert $assert
     */
    public function addConstraint(Assert $assert);

    /**
     * Create a validator instance.
     *
     * @return Validator
     */
    public static function create();
}
