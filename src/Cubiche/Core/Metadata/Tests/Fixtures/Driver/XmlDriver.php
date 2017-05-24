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
use Cubiche\Core\Metadata\Driver\AbstractXmlDriver;
use Cubiche\Core\Metadata\PropertyMetadata;

/**
 * XmlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class XmlDriver extends AbstractXmlDriver
{
    /**
     * {@inheritdoc}
     */
    protected function loadMappingFile($file)
    {
        $result = array();
        $xmlElement = simplexml_load_file($file);
        foreach (array('aggregate-root', 'entity') as $type) {
            if (isset($xmlElement->$type)) {
                foreach ($xmlElement->$type as $documentElement) {
                    $documentName = (string) $documentElement['name'];
                    $result[$documentName] = $documentElement;
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function addMetadataFor(\SimpleXMLElement $xmlRoot, ClassMetadataInterface $classMetadata)
    {
        if (isset($xmlRoot->field)) {
            foreach ($xmlRoot->field as $field) {
                $mapping = $this->getMappingAttributes($field);
                $booleanAttributes = array('id', 'unique');
                foreach ($mapping as $key => $value) {
                    if (in_array($key, $booleanAttributes)) {
                        $mapping[$key] = ('true' === $mapping[$key]);
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
