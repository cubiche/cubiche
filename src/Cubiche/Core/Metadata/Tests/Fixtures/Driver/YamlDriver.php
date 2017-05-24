<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures\Driver;

use Cubiche\Core\Metadata\ClassMetadataInterface;
use Cubiche\Core\Metadata\Driver\AbstractYamlDriver;
use Cubiche\Core\Metadata\PropertyMetadata;

/**
 * YamlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class YamlDriver extends AbstractYamlDriver
{
    /**
     * {@inheritdoc}
     */
    protected function addMetadataFor(array $config, ClassMetadataInterface $classMetadata)
    {
        if (isset($config['fields'])) {
            foreach ($config['fields'] as $fieldName => $mapping) {
                if (!isset($mapping['fieldName'])) {
                    $mapping['fieldName'] = $fieldName;
                }

                $booleanAttributes = array('id', 'unique');
                foreach ($mapping as $key => $value) {
                    if (in_array($key, $booleanAttributes)) {
                        $mapping[$key] = (true === $mapping[$key]);
                    }
                }

                $this->addFieldMapping($classMetadata, $mapping);
            }
        }
    }

    /**
     * @param ClassMetadataInterface $classMetadata
     * @param array                  $mapping
     */
    private function addFieldMapping(ClassMetadataInterface $classMetadata, $mapping)
    {
        if (isset($mapping['name'])) {
            $name = $mapping['name'];
            if (!isset($mapping['fieldName'])) {
                $mapping['fieldName'] = $name;
            }
        } elseif (isset($mapping['fieldName'])) {
            $name = $mapping['fieldName'];
            if (!isset($mapping['name'])) {
                $mapping['name'] = $name;
            }
        } else {
            throw new \InvalidArgumentException('Cannot infer a name from the mapping');
        }

        $isIdentifier = false;
        if (isset($mapping['id']) && $mapping['id'] === true) {
            $isIdentifier = true;
            $mapping['name'] = '_id';
        }

        $propertyMetadata = new PropertyMetadata(
            $classMetadata->className(),
            $mapping['fieldName']
        );

        $propertyMetadata->addMetadata('identifier', $isIdentifier);
        $propertyMetadata->addMetadata('name', $mapping['name']);

        if (isset($mapping['type'])) {
            $propertyMetadata->addMetadata('type', $mapping['type']);
            if (isset($mapping['of'])) {
                $propertyMetadata->addMetadata('of', $mapping['of']);
            }
        }

        $classMetadata->addPropertyMetadata($propertyMetadata);
    }
}
