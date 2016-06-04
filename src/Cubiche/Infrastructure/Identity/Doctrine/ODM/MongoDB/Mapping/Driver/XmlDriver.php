<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB\Mapping\Driver;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\MappingException;
use Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB\Mapping\IdentifyPropertyMetadata;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver as BaseXmlDriver;
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
        foreach ($xmlRoot->xpath('//cubiche:id') as $item) {
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
                    'The cubiche:id definition should have a "type" value',
                    $classMetadata->name,
                    $fieldName
                );
            }

            $idType = $itemMapping['type'];

            if ($field->getName() == 'field') {
                if (!isset($fieldMapping['type']) ||
                    (isset($fieldMapping['type']) && $fieldMapping['type'] !== 'Identifier')
                ) {
                    throw MappingException::inField(
                        'The cubiche:id configuration is only for id fields',
                        $classMetadata->name,
                        $fieldName
                    );
                }

                $propertyMetadata = new IdentifyPropertyMetadata($classMetadata->name, $fieldName);
                $propertyMetadata->setType($idType);

                $classMetadata->addPropertyMetadata($propertyMetadata);
            } else {
                throw MappingException::inField(
                    'The cubiche:id configuration is only for id fields',
                    $classMetadata->name,
                    $fieldName
                );
            }
        }
    }
}
