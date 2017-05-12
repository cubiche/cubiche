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

use Cubiche\Core\Metadata\Exception\MappingException;
use Metadata\Driver\AbstractFileDriver as BaseFileDriver;
use Metadata\Driver\FileLocatorInterface;

/**
 * AbstractFileDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractFileDriver extends BaseFileDriver implements DriverInterface
{
    /**
     * @var array|null
     */
    protected $classCache;

    /**
     * AbstractFileDriver constructor.
     *
     * @param FileLocatorInterface $locator
     */
    public function __construct(FileLocatorInterface $locator)
    {
        parent::__construct($locator);

        $this->classCache = [];
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
            throw MappingException::invalidMappingFile($className, $file);
        }

        $this->classCache = array_merge($this->classCache, $result);

        return $result[$className];
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
