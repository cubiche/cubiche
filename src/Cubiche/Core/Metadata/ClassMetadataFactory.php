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

use Metadata\ClassHierarchyMetadata;
use Metadata\MetadataFactory;

/**
 * ClassMetadataFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassMetadataFactory extends MetadataFactory
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

    /**
     * @param string $className
     *
     * @return ClassMetadata|MergeableClassMetadata|null
     */
    public function getMetadataForClass($className)
    {
        $classMetadata = parent::getMetadataForClass($className);
        if ($classMetadata !== null && $classMetadata instanceof ClassHierarchyMetadata) {
            return $classMetadata->getOutsideClassMetadata();
        }

        return $classMetadata;
    }
}
