<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Factory;

use Cubiche\Core\Metadata\Driver\ChainDriver;
use Cubiche\Core\Metadata\Driver\DriverInterface;
use Cubiche\Core\Metadata\Driver\MergeableDriver;
use Cubiche\Core\Metadata\Factory\DriverFactory as BaseDriverFactory;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Locator\DoctrineAdapterLocator;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Persistence\Mapping\Driver\FileDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * DriverFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DriverFactory extends BaseDriverFactory
{
    /**
     * Create a driver from a Doctrine object manager.
     *
     * @param ObjectManager $om
     *
     * @return DriverInterface
     */
    public static function driversFromManager(ObjectManager $om)
    {
        return self::driversFromMetadataDriver($om->getConfiguration()->getMetadataDriverImpl());
    }

    /**
     * Create a driver from a Doctrine metadata driver.
     *
     * @param MappingDriver $omDriver
     *
     * @return DriverInterface
     */
    public static function driversFromMetadataDriver(MappingDriver $omDriver)
    {
        return static::instance()->fromMetadataDriver($omDriver);
    }

    /**
     * Create the cubiche drivers from the Doctrine metadata drivers.
     *
     * @param MappingDriver $omDriver
     *
     * @return DriverInterface
     */
    protected function fromMetadataDriver(MappingDriver $omDriver)
    {
        if ($omDriver instanceof MappingDriverChain) {
            $drivers = array();
            foreach ($omDriver->getDrivers() as $nestedOmDriver) {
                $drivers[] = $this->fromMetadataDriver($nestedOmDriver);
            }

            return new ChainDriver($drivers);
        }

        if ($omDriver instanceof AnnotationDriver) {
            return new MergeableDriver(
                $this->createAnnotationDriver($omDriver->getReader())
            );
        }

        if ($omDriver instanceof FileDriver) {
            $reflClass = new \ReflectionClass($omDriver);

            $driverName = $reflClass->getShortName();
            if (strpos($driverName, 'Simplified') !== false) {
                $driverName = str_replace('Simplified', '', $driverName);
            }

            return new MergeableDriver(
                call_user_func(
                    array(static::class, 'create'.$driverName),
                    new DoctrineAdapterLocator($omDriver->getLocator())
                )
            );
        }

        throw new \InvalidArgumentException('Cannot adapt Doctrine driver of class '.get_class($omDriver));
    }
}
