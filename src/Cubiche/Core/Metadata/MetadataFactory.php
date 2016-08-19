<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Metadata;

use Metadata\MetadataFactory as BaseMetadataFactory;

/**
 * MetadataFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MetadataFactory extends BaseMetadataFactory
{
    /**
     * Forces the factory to load the metadata of all classes known to the underlying
     * mapping driver.
     *
     * @return array.
     */
    public function getAllMetadata()
    {
        $metadata = [];
        foreach ($this->getAllClassNames() as $className) {
            $metadata[] = $this->getMetadataForClass($className);
        }

        return $metadata;
    }
}
