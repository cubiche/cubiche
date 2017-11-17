<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Metadata\Driver;

use Cubiche\Core\Metadata\ClassMetadataInterface;
use Cubiche\Core\Metadata\PropertyMetadata;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Exception\MappingException;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Driver\XmlDriver as BaseXmlDriver;

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
    protected function addMetadataFor(\SimpleXMLElement $xmlRoot, ClassMetadataInterface $classMetadata)
    {
        $this->addMetadataForEnum($xmlRoot, $classMetadata);

        $this->addMetadataForType('decimal', $xmlRoot, $classMetadata);
        $this->addMetadataForType('integer', $xmlRoot, $classMetadata);
        $this->addMetadataForType('real', $xmlRoot, $classMetadata);
        $this->addMetadataForType('string', $xmlRoot, $classMetadata);
    }

    /**
     * {@inheritdoc}
     */
    protected function addMetadataForEnum(\SimpleXMLElement $xmlRoot, ClassMetadataInterface $classMetadata)
    {
        foreach ($xmlRoot->xpath('//cubiche:enum') as $item) {
            // get the field tag
            $field = $item->xpath('..')[0];
            $fieldMapping = $this->getMappingAttributes($field);
            $fieldName = $fieldMapping['name'];

            $itemMapping = $this->getMappingAttributes($item);
            foreach ($item->attributes() as $key => $value) {
                $itemMapping[$key] = (string) $value;
            }

            if (!isset($itemMapping['type'])) {
                throw MappingException::inField(
                    'The cubiche:enum definition should have a "type" value',
                    $classMetadata->className(),
                    $fieldName
                );
            }

            $enumType = $itemMapping['type'];

            if ($field->getName() == 'field') {
                if (isset($fieldMapping['id']) && $fieldMapping['id'] !== false) {
                    throw MappingException::inField(
                        'The cubiche:enum configuration is only for field tags that is not an id',
                        $classMetadata->className(),
                        $fieldName
                    );
                }

                if (!isset($fieldMapping['type']) ||
                    (isset($fieldMapping['type']) && $fieldMapping['type'] !== 'CubicheType')
                ) {
                    throw MappingException::inField(
                        'The cubiche:enum parent should have a "type" value equal to CubicheType',
                        $classMetadata->className(),
                        $fieldName
                    );
                }

                $propertyMetadata = new PropertyMetadata($classMetadata->className(), $fieldName);

                $propertyMetadata->addMetadata('namespace', 'enum');
                $propertyMetadata->addMetadata('type', $enumType);

                $classMetadata->addPropertyMetadata($propertyMetadata);
            } else {
                throw MappingException::inField(
                    'The cubiche:enum configuration is only for id fields',
                    $classMetadata->className(),
                    $fieldName
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function addMetadataForType($type, \SimpleXMLElement $xmlRoot, ClassMetadataInterface $classMetadata)
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
                        $classMetadata->className(),
                        $fieldName
                    );
                }

                if (!isset($fieldMapping['type']) ||
                    (isset($fieldMapping['type']) && $fieldMapping['type'] !== 'CubicheType')
                ) {
                    throw MappingException::inField(
                        'The cubiche:'.$type.' parent should have a "type" value equal to CubicheType',
                        $classMetadata->className(),
                        $fieldName
                    );
                }

                $propertyMetadata = new PropertyMetadata($classMetadata->className(), $fieldName);
                $propertyMetadata->addMetadata('namespace', $type);

                $classMetadata->addPropertyMetadata($propertyMetadata);
            } else {
                throw MappingException::inField(
                    'The cubiche:'.$type.' configuration is only for id fields',
                    $classMetadata->className(),
                    $fieldName
                );
            }
        }
    }
}
