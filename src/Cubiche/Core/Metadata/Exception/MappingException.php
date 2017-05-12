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
use Exception;

/**
 * MappingException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MappingException extends RuntimeException
{
    /**
     * @param Exception|null $cause
     *
     * @return MappingException
     */
    public static function pathRequired(Exception $cause = null)
    {
        return new static(
            'The paths is required to retrieve all class names.',
            0,
            $cause
        );
    }

    /**
     * @param string         $path
     * @param Exception|null $cause
     *
     * @return MappingException
     */
    public static function invalidDirectory($path, Exception $cause = null)
    {
        return new static(sprintf(
            'The given path %s must have a valid directory.',
            $path
        ), 0, $cause);
    }

    /**
     * @param string    $entityName
     * @param string    $fileName
     * @param Exception $cause
     *
     * @return MappingException
     */
    public static function invalidMappingFile($entityName, $fileName, Exception $cause = null)
    {
        return new static(sprintf(
            'Invalid mapping file %s for class %s.',
            $fileName,
            $entityName
        ), 0, $cause);
    }

    /**
     * @param string    $className
     * @param Exception $cause
     *
     * @return MappingException
     */
    public static function classNotFound($className, Exception $cause = null)
    {
        return new static(sprintf(
            'The class %s was not found in the chain configured drivers.',
            $className
        ), 0, $cause);
    }
}
