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

use Assert\Assertion as BaseAssert;

/**
 * Assert class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Assert extends BaseAssert
{
    const INVALID_ALWAYS_INVALID = 230;
    const INVALID_ALPHA = 231;
    const INVALID_NONE_OF = 232;

    /**
     * @var string
     */
    protected static $exceptionClass = 'Cubiche\Core\Validator\Exception\InvalidArgumentException';

    /**
     * @param mixed $value
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
     */
    public function alwaysValid($value, $message = null, $propertyPath = null)
    {
        return true;
    }

    /**
     * @param mixed $value
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
     */
    public function alwaysInvalid($value, $message = null, $propertyPath = null)
    {
        $message = \sprintf(
            static::generateMessage($message ?: 'Value "%s" is always invalid.'),
            static::stringify($value)
        );

        throw static::createException($value, $message, static::INVALID_ALWAYS_INVALID, $propertyPath);
    }

    /**
     * @param mixed $value
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
     */
    public function alpha($value, $message = null, $propertyPath = null)
    {
        if (!ctype_alpha($value)) {
            $message = \sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be aplha, type %s given.'),
                static::stringify($value),
                \gettype($value)
            );

            throw static::createException($value, $message, static::INVALID_ALPHA, $propertyPath);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function stringify($value)
    {
        return BaseAssert::stringify($value);
    }
}
