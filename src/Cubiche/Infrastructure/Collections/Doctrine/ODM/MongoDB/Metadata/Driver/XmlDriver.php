<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Metadata\Driver;

use Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Metadata\CollectionPropertyMetadata;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Driver\XmlDriver as BaseXmlDriver;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Exception\MappingException;
use Cubiche\Core\Metadata\MergeableClassMetadata;

/**
 * XmlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class XmlDriver extends BaseXmlDriver
{
    /**
     * {@inheritdoc}
     */
    protected function addMetadataFor(\SimpleXMLElement $xmlRoot, MergeableClassMetadata $classMetadata)
    {
        foreach ($xmlRoot->xpath('//cubiche:collection') as $item) {
            // get the field tag
            $field = $item->xpath('..')[0];
            $fieldMapping = $this->getMappingAttributes($field);
            $fieldName = isset($fieldMapping['name']) ? $fieldMapping['name'] : $fieldMapping['field'];

            $itemMapping = $this->getMappingAttributes($item, array('of' => null));
            if (!isset($itemMapping['type'])) {
                throw MappingException::inField(
                    'The cubiche:collection definition should have a "type" value',
                    $classMetadata->name,
                    $fieldName
                );
            }

            $collectionType = $itemMapping['type'];
            $collectionOf = $itemMapping['of'];

            if ($field->getName() == 'field') {
                if (isset($fieldMapping['id']) && $fieldMapping['id'] !== false) {
                    throw MappingException::inField(
                        'The cubiche:collection configuration is only for field tags that is not an id',
                        $classMetadata->name,
                        $fieldName
                    );
                }

                if (!isset($fieldMapping['type']) ||
                    (isset($fieldMapping['type']) && $fieldMapping['type'] !== 'CubicheType')
                ) {
                    throw MappingException::inField(
                        'The cubiche:collection parent should have a "type" value equal to CubicheType',
                        $classMetadata->name,
                        $fieldName
                    );
                }

                $propertyMetadata = new CollectionPropertyMetadata($classMetadata->name, $fieldName);
                $propertyMetadata->setType($collectionType);
                $propertyMetadata->setOf($collectionOf);

                $classMetadata->addPropertyMetadata($propertyMetadata);
            } elseif ($field->getName() == 'embed-many' || $field->getName() == 'reference-many') {
                if (isset($fieldMapping['field'])) {
                    $field = $fieldMapping['field'];
                } else {
                    throw MappingException::inField(
                        'Cannot infer a field',
                        $classMetadata->name,
                        $fieldName
                    );
                }

                $propertyMetadata = new CollectionPropertyMetadata($classMetadata->name, $field);
                $propertyMetadata->setType($collectionType);

                $classMetadata->addPropertyMetadata($propertyMetadata);
            } else {
                throw MappingException::inField(
                    'The cubiche:collection configuration is only for field, embed-many or reference-many tags',
                    $classMetadata->name,
                    $fieldName
                );
            }
        }
    }
}
