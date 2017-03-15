<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Mapping\Driver;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\MappingException;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver as BaseXmlDriver;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\PropertyMetadata;
use Metadata\MergeableClassMetadata;

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
        $this->addMetadataForType('email', $xmlRoot, $classMetadata);
        $this->addMetadataForType('hostname', $xmlRoot, $classMetadata);
        $this->addMetadataForType('ip', $xmlRoot, $classMetadata);
        $this->addMetadataForType('path', $xmlRoot, $classMetadata);
        $this->addMetadataForType('port', $xmlRoot, $classMetadata);
        $this->addMetadataForType('url', $xmlRoot, $classMetadata);
    }

    /**
     * {@inheritdoc}
     */
    protected function addMetadataForType($type, \SimpleXMLElement $xmlRoot, MergeableClassMetadata $classMetadata)
    {
        foreach ($xmlRoot->xpath('//cubiche:'.$type) as $item) {
            // get the field tag
            $field = $item->xpath('..')[0];
            $fieldMapping = $this->getMappingAttributes($field);
            $fieldName = $fieldMapping['name'];

            if ($field->getName() == 'field') {
                if (isset($fieldMapping['id']) && $fieldMapping['id'] !== false) {
                    throw MappingException::inField(
                        'The cubiche:'.$type.' configuration is only for field tags that is not an id',
                        $classMetadata->name,
                        $fieldName
                    );
                }

                if (!isset($fieldMapping['type']) ||
                    (isset($fieldMapping['type']) && $fieldMapping['type'] !== 'CubicheType')
                ) {
                    throw MappingException::inField(
                        'The cubiche:'.$type.' parent should have a "type" value equal to CubicheType',
                        $classMetadata->name,
                        $fieldName
                    );
                }

                $propertyMetadata = new PropertyMetadata($classMetadata->name, $fieldName, $type);

                $classMetadata->addPropertyMetadata($propertyMetadata);
            } else {
                throw MappingException::inField(
                    'The cubiche:'.$type.' configuration is only for id fields',
                    $classMetadata->name,
                    $fieldName
                );
            }
        }
    }
}
