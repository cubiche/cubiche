<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Mapping\Driver;

use Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Mapping\CollectionPropertyMetadata;
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
        foreach ($xmlRoot->xpath('//cubiche:collection') as $item) {
            $mapping = array('of' => null);
            foreach ($item->attributes() as $key => $value) {
                $mapping[$key] = (string) $value;
            }

            if (!isset($mapping['type'])) {
                throw new \InvalidArgumentException('The cubiche:collection definition should have a "type" value.');
            }

            $collectionType = $mapping['type'];
            $collectionOf = $mapping['of'];

            // get the parent tag
            $tag = $item->xpath('..')[0];
            $mapping = array();
            foreach ($tag->attributes() as $key => $value) {
                $mapping[$key] = (string) $value;
            }

            if ($tag->getName() == 'field') {
                if (isset($mapping['name'])) {
                    $name = $mapping['name'];
                } else {
                    throw new \InvalidArgumentException('Cannot infer a name from the mapping');
                }

                $propertyMetadata = new CollectionPropertyMetadata($classMetadata->name, $name);
                $propertyMetadata->type = $collectionType;
                $propertyMetadata->of = $collectionOf;

                $propertyMetadata->typeClassName =
                    'Cubiche\\Infrastructure\\Collections\\Doctrine\\ODM\\MongoDB\\Types\\'.
                    $collectionType.'Type'
                ;

                $propertyMetadata->persistenClassName =
                    'Cubiche\\Infrastructure\\Collections\\Doctrine\\Common\\Collections\\Persistent'.
                    $collectionType
                ;

                $classMetadata->addPropertyMetadata($propertyMetadata);
            } elseif ($tag->getName() == 'embed-many' || $tag->getName() == 'reference-many') {
                if (isset($mapping['field'])) {
                    $field = $mapping['field'];
                } else {
                    throw new \InvalidArgumentException('Cannot infer a field from the mapping');
                }

                $propertyMetadata = new CollectionPropertyMetadata($classMetadata->name, $field);
                $propertyMetadata->type = $collectionType;

                $propertyMetadata->typeClassName =
                    'Cubiche\\Infrastructure\\Collections\\Doctrine\\ODM\\MongoDB\\Types\\'.
                    $collectionType.'Type'
                ;

                $propertyMetadata->persistenClassName =
                    'Cubiche\\Infrastructure\\Collections\\Doctrine\\Common\\Collections\\Persistent'.
                    $collectionType
                ;

                $classMetadata->addPropertyMetadata($propertyMetadata);
            }
        }
    }
}
