<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Exception;

use RuntimeException;

/**
 * MappingException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MappingException extends RuntimeException
{
    /**
     * @return MappingException
     */
    public static function pathRequired()
    {
        return new static('The paths is required to retrieve all class names.');
    }

    /**
     * @param string $path
     *
     * @return MappingException
     */
    public static function invalidDirectory($path)
    {
        return new static(sprintf(
            'The given path %s must have a valid directory.',
            $path
        ));
    }

    /**
     * @param string $className
     *
     * @return MappingException
     */
    public static function classNotFound($className)
    {
        return new static(sprintf(
            'The class %s was not found in the chain configured drivers.',
            $className
        ));
    }

    /**
     * @param string $className
     * @param string $fileName
     *
     * @return MappingException
     */
    public static function invalidMapping($className, $fileName)
    {
        return new static(sprintf(
            'Invalid mapping file %s for class %s.',
            $fileName,
            $className
        ));
    }
}
