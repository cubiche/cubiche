<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Tests\Fixtures;

use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * Assert class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Assert
{
    /**
     * @param mixed $value
     * @param null  $message
     * @param null  $propertyPath
     */
    public static function uniqueId($value, $message = null, $propertyPath = null)
    {
        throw new InvalidArgumentException(
            'Expexted unique id',
            \Cubiche\Core\Validator\Assert::INVALID_CUSTOM_ASSERT,
            $propertyPath,
            $value
        );
    }

    /**
     * @param mixed $value
     * @param null  $message
     * @param null  $propertyPath
     */
    public function uniqueEmail($value, $message = null, $propertyPath = null)
    {
        throw new InvalidArgumentException(
            'Expexted unique email',
            \Cubiche\Core\Validator\Assert::INVALID_CUSTOM_ASSERT,
            $propertyPath,
            $value
        );
    }
}
