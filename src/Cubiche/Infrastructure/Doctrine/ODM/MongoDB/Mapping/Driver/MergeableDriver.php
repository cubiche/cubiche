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
use Metadata\Driver\DriverInterface;

/**
 * MergeableDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MergeableDriver implements DriverInterface
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
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new MergeableClassMetadata($class->getName());
        foreach ($this->drivers as $driver) {
            if (null !== $metadata = $driver->loadMetadataForClass($class)) {
                $classMetadata->merge($metadata);
            }
        }

        return $classMetadata;
    }
}
