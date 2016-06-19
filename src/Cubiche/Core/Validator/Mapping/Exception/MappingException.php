<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Validator\Mapping\Exception;

/**
 * MappingException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MappingException extends \Exception
{
    /**
     * @param string $message
     * @param string $className
     * @param string $fieldName
     *
     * @return static
     */
    public static function withMessage($message, $className, $fieldName)
    {
        return new static(sprintf(
            $message,
            $className,
            $fieldName
        ));
    }
}
