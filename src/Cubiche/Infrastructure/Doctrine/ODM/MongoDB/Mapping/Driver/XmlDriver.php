<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\Driver;

use Metadata\MergeableClassMetadata;

/**
 * XmlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class XmlDriver extends FileDriver
{
    /**
     * @param \ReflectionClass $class
     *
     * @return MergeableClassMetadata
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        /* @var $xmlRoot \SimpleXMLElement */
        $xmlRoot = $this->getElement($class->getName());
        if (!$xmlRoot) {
            return;
        }

        $xmlRoot->registerXPathNamespace('cubiche', 'cubiche');

        $classMetadata = new MergeableClassMetadata($class->getName());
        $this->addMetadataFor($xmlRoot, $classMetadata);

        return $classMetadata;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadMappingFile($file)
    {
        $result = array();
        $xmlElement = simplexml_load_file($file);
        foreach (array('document', 'embedded-document', 'mapped-superclass') as $type) {
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
     * @param \SimpleXMLElement      $xmlRoot
     * @param MergeableClassMetadata $classMetadata
     */
    abstract protected function addMetadataFor(\SimpleXMLElement $xmlRoot, MergeableClassMetadata $classMetadata);
}
