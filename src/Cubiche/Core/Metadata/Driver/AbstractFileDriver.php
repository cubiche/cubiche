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

use Cubiche\Core\Metadata\ClassMetadataInterface;
use Cubiche\Core\Metadata\Exception\MappingException;
use Cubiche\Core\Metadata\Locator\FileLocatorInterface;

/**
 * AbstractFileDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractFileDriver implements DriverInterface
{
    /**
     * @var FileLocatorInterface
     */
    protected $locator;

    /**
     * @var array
     */
    protected $classCache;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * AbstractFileDriver constructor.
     *
     * @param FileLocatorInterface $locator
     */
    public function __construct(FileLocatorInterface $locator)
    {
        $this->locator = $locator;
        $this->classCache = [];
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass($className)
    {
        if (null === $path = $this->locator->findMappingFile($className, $this->getExtension())) {
            return;
        }

        $this->fileName = $path;

        return $this->loadMetadataFromFile($className, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        return $this->locator->getAllClassNames($this->getExtension());
    }

    /**
     * Gets the element of schema meta data for the class from the mapping file.
     * This will lazily load the mapping file if it is not loaded yet.
     *
     * @param string $className
     * @param string $file
     *
     * @return mixed
     *
     * @throws MappingException
     */
    public function getElement($className, $file)
    {
        if (isset($this->classCache[$className])) {
            return $this->classCache[$className];
        }

        $result = $this->loadMappingFile($file);
        if (!isset($result[$className])) {
            throw MappingException::invalidMapping($className, $file);
        }

        $this->classCache = array_merge($this->classCache, $result);

        return $result[$className];
    }

    /**
     * Parses the content of the file, and converts it to the desired metadata.
     *
     * @param string $className
     * @param string $file
     *
     * @return ClassMetadataInterface
     */
    abstract protected function loadMetadataFromFile($className, $file);

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    abstract protected function getExtension();

    /**
     * Loads a mapping file with the given name and returns a map
     * from class/entity names to their corresponding file driver elements.
     *
     * @param string $file The mapping file to load.
     *
     * @return array
     */
    abstract protected function loadMappingFile($file);
}
