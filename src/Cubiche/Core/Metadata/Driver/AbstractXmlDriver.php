<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Driver;

use Cubiche\Core\Metadata\MergeableClassMetadata;

/**
 * AbstractXmlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractXmlDriver extends AbstractFileDriver
{
    /**
     * @param \SimpleXMLElement $item
     * @param array             $default
     *
     * @return array
     */
    protected function getMappingAttributes(\SimpleXMLElement $item, array $default = array())
    {
        $mapping = $default;
        foreach ($item->attributes() as $key => $value) {
            $mapping[$key] = (string) $value;
        }

        return $mapping;
    }

    /**
     * Parses the content of the file, and converts it to the desired metadata.
     *
     * @param \ReflectionClass $class
     * @param string           $file
     *
     * @return MergeableClassMetadata|null
     */
    protected function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        /* @var $xmlRoot \SimpleXMLElement */
        $xmlRoot = $this->getElement($class->getName(), $file);
        if (!$xmlRoot) {
            return;
        }

        $xmlRoot->registerXPathNamespace('cubiche', 'cubiche');

        $classMetadata = new MergeableClassMetadata($class->getName());
        $this->addMetadataFor($xmlRoot, $classMetadata);

        return $classMetadata;
    }

    /**
     * @param \SimpleXMLElement      $xmlRoot
     * @param MergeableClassMetadata $classMetadata
     */
    abstract protected function addMetadataFor(\SimpleXMLElement $xmlRoot, MergeableClassMetadata $classMetadata);

    /**
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return '.xml';
    }
}
