<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Locator;

/**
 * FileLocator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface FileLocatorInterface
{
    /**
     * Locates mapping file for the given class name and extension.
     *
     * @param string $className
     * @param string $extension
     *
     * @return string
     */
    public function findMappingFile($className, $extension);

    /**
     * Gets all class names that are found with this file locator.
     *
     * @param string $extension
     *
     * @return array
     */
    public function getAllClassNames($extension);
}
