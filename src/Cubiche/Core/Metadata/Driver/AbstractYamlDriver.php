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
     * Parses the content of the file, and converts it to the desired metadata.
     *
     * @param \ReflectionClass $class
     * @param string           $file
     *
     * @return MergeableClassMetadata|null
     */
    protected function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $config = $this->getElement($class->getName(), $file);
        if (empty($config)) {
            return;
        }

        $classMetadata = new MergeableClassMetadata($class->getName());
        $this->addMetadataFor($config, $classMetadata);

        return $classMetadata;
    }

    /**
     * Loads a mapping file with the given name and returns a map
     * from class/entity names to their corresponding file driver elements.
     *
     * @param string $file The mapping file to load.
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function loadMappingFile($file)
    {
        try {
            return Yaml::parse(file_get_contents($file));
        } catch (ParseException $e) {
            $e->setParsedFile($file);
            throw $e;
        }
    }

    /**
     * @param array                  $config
     * @param MergeableClassMetadata $classMetadata
     */
    abstract protected function addMetadataFor(array $config, MergeableClassMetadata $classMetadata);

    /**
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return '.yml';
    }
}
