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

/**
 * ChainDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ChainDriver implements DriverInterface
{
    /**
     * @var DriverInterface[]
     */
    protected $drivers;

    /**
     * DriverChain constructor.
     *
     * @param array $drivers
     */
    public function __construct(array $drivers = array())
    {
        foreach ($drivers as $driver) {
            $this->addDriver($driver);
        }
    }

    /**
     * @param DriverInterface $driver
     */
    public function addDriver(DriverInterface $driver)
    {
        $this->drivers[] = $driver;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return mixed
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        foreach ($this->drivers as $driver) {
            if (null !== $metadata = $driver->loadMetadataForClass($class)) {
                return $metadata;
            }
        }

        throw MappingException::classNotFound($class->getName());
    }

    /**
     * Gets all the metadata class names known to this driver.
     *
     * @return array
     */
    public function getAllClassNames()
    {
        foreach ($this->drivers as $driver) {
            if (null !== $classNames = $driver->getAllClassNames()) {
                return $classNames;
            }
        }

        return array();
    }
}
