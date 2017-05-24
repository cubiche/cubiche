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

use Cubiche\Core\Collections\ArrayCollection\ArrayList;
use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\Exception\MappingException;

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
     * MergeableDriver constructor.
     *
     * @param array $drivers
     */
    public function __construct(array $drivers = array())
    {
        $this->drivers = new ArrayList();
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
        $classMetadata = null;
        /** @var $driver DriverInterface */
        foreach ($this->drivers->toArray() as $driver) {
            try {
                $metadata = $driver->loadMetadataForClass($className);
            } catch (MappingException $e) {
                continue;
            }

            if ($metadata !== null) {
                if ($classMetadata === null) {
                    $classMetadata = new ClassMetadata($className);
                }

                $classMetadata->merge($metadata);
            }
        }

        return $classMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        $classNames = [];
        /** @var $driver DriverInterface */
        foreach ($this->drivers->toArray() as $driver) {
            $classNames = array_merge($classNames, $driver->getAllClassNames());
        }

        return $classNames;
    }
}
