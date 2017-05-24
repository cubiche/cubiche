<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Driver;

use Cubiche\Core\Metadata\ClassMetadataInterface;

/**
 * AbstractAnnotationDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface DriverInterface
{
    /**
     * Loads the metadata for the specified class into the provided container.
     *
     * @param string $className
     *
     * @return ClassMetadataInterface|null
     */
    public function loadMetadataForClass($className);

    /**
     * Gets the names of all mapped classes known to this driver.
     *
     * @return array
     */
    public function getAllClassNames();
}
