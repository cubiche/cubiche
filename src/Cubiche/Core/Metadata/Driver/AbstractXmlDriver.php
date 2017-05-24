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

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\ClassMetadataInterface;
use Cubiche\Core\Metadata\Exception\MappingException;

/**
 * AbstractXmlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractXmlDriver extends AbstractFileDriver
{
    /**
     * {@inheritdoc}
     */
    protected function loadMetadataFromFile($className, $file)
    {
        /* @var $xmlRoot \SimpleXMLElement */
        $xmlRoot = $this->getElement($className, $file);
        $xmlRoot->registerXPathNamespace('cubiche', 'cubiche');

        try {
            $classMetadata = new ClassMetadata($className);
        } catch (\ReflectionException $e) {
            throw MappingException::classNotFound($className);
        }

        $this->addMetadataFor($xmlRoot, $classMetadata);

        return $classMetadata;
    }

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
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return '.xml';
    }

    /**
     * @param \SimpleXMLElement      $xmlRoot
     * @param ClassMetadataInterface $classMetadata
     */
    abstract protected function addMetadataFor(\SimpleXMLElement $xmlRoot, ClassMetadataInterface $classMetadata);
}
