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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMapInterface;
use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Metadata\Exception\MappingException;

/**
 * ChainDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ChainDriver implements DriverInterface
{
    /**
     * @var ArrayHashMapInterface
     */
    protected $drivers;

    /**
     * The default driver.
     *
     * @var DriverInterface
     */
    protected $defaultDriver;

    /**
     * ChainDriver constructor.
     *
     * @param array                $drivers
     * @param DriverInterface|null $defaultDriver
     */
    public function __construct(array $drivers = array(), DriverInterface $defaultDriver = null)
    {
        $this->drivers = new ArrayList();
        $this->defaultDriver = $defaultDriver;

        foreach ($drivers as $driver) {
            $this->addDriver($driver);
        }
    }

    /**
     * @param DriverInterface $driver
     */
    public function addDriver(DriverInterface $driver)
    {
        $this->drivers->add($driver);
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass($className)
    {
        /** @var $driver DriverInterface */
        foreach ($this->drivers->toArray() as $driver) {
            if (null !== $metadata = $driver->loadMetadataForClass($className)) {
                return $metadata;
            }
        }

        if ($this->defaultDriver !== null) {
            return $this->defaultDriver->loadMetadataForClass($className);
        }

        throw MappingException::classNotFound($className);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        $classNames = [];
        foreach ($this->drivers->toArray() as $driver) {
            $classNames = array_merge($classNames, $driver->getAllClassNames());
        }

        if ($this->defaultDriver !== null) {
            $classNames = array_merge($classNames, $this->defaultDriver->getAllClassNames());
        }

        return $classNames;
    }
}
