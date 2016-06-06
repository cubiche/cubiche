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

use Doctrine\Common\Persistence\Mapping\Driver\FileLocator;
use Metadata\Driver\DriverInterface;

/**
 * FileDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class FileDriver implements DriverInterface
{
    /**
     * @var FileLocator
     */
    protected $locator;

    /**
     * @var array|null
     */
    protected $classCache;

    /**
     * @var string|null
     */
    protected $globalBasename;

    /**
     * Constructor.
     *
     * @param FileLocator $locator
     */
    public function __construct(FileLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * Sets the global basename.
     *
     * @param string $file
     */
    public function setGlobalBasename($file)
    {
        $this->globalBasename = $file;
    }

    /**
     * Retrieves the global basename.
     *
     * @return string|null
     */
    public function getGlobalBasename()
    {
        return $this->globalBasename;
    }

    /**
     * Gets the element of schema meta data for the class from the mapping file.
     * This will lazily load the mapping file if it is not loaded yet.
     *
     * @param string $className
     *
     * @return array The element of schema meta data.
     *
     * @throws \Exception
     */
    public function getElement($className)
    {
        if (!$this->locator->fileExists($className)) {
            return;
        }

        if ($this->classCache === null) {
            $this->initialize();
        }

        if (isset($this->classCache[$className])) {
            return $this->classCache[$className];
        }

        $result = $this->loadMappingFile($this->locator->findMappingFile($className));
        if (!isset($result[$className])) {
            throw new \Exception(sprintf(
                'Invalid mapping file %s for class %s.',
                $className,
                str_replace('\\', '.', $className).$this->locator->getFileExtension()
            ));
        }

        return $result[$className];
    }

    /**
     * Initializes the class cache from all the global files.
     *
     * Using this feature adds a substantial performance hit to file drivers as
     * more metadata has to be loaded into memory than might actually be
     * necessary. This may not be relevant to scenarios where caching of
     * metadata is in place, however hits very hard in scenarios where no
     * caching is used.
     */
    protected function initialize()
    {
        $this->classCache = [];
        if (null !== $this->globalBasename) {
            foreach ($this->locator->getPaths() as $path) {
                $file = $path.'/'.$this->globalBasename.$this->locator->getFileExtension();
                if (is_file($file)) {
                    $this->classCache = array_merge(
                        $this->classCache,
                        $this->loadMappingFile($file)
                    );
                }
            }
        }
    }

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
