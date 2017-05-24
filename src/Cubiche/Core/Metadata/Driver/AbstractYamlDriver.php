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
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * AbstractYamlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractYamlDriver extends AbstractFileDriver
{
    /**
     * @var string
     */
    protected $className;

    /**
     * {@inheritdoc}
     */
    protected function loadMetadataFromFile($className, $file)
    {
        $this->className = $className;
        $config = $this->getElement($className, $file);

        try {
            $classMetadata = new ClassMetadata($className);
        } catch (\ReflectionException $e) {
            throw MappingException::classNotFound($className);
        }

        $this->addMetadataFor($config, $classMetadata);

        return $classMetadata;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadMappingFile($file)
    {
        try {
            return Yaml::parse(file_get_contents($file));
        } catch (ParseException $e) {
            throw MappingException::invalidMapping($this->className, $file);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return '.yml';
    }

    /**
     * @param array                  $config
     * @param ClassMetadataInterface $classMetadata
     */
    abstract protected function addMetadataFor(array $config, ClassMetadataInterface $classMetadata);
}
