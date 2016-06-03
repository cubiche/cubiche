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

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver as DoctrineAnnotationDriver;
use Doctrine\Common\Persistence\Mapping\Driver\FileDriver as DoctrineFileDriver;
use Doctrine\Common\Persistence\Mapping\Driver\FileLocator;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\Common\Persistence\ObjectManager;
use Metadata\Driver\DriverChain;
use Metadata\Driver\DriverInterface;

/**
 * DriverFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DriverFactory
{
    /**
     * @var DriverFactory
     */
    protected static $instance = null;

    /**
     * @var array
     */
    protected $drivers = array();

    /**
     * @param string $driver
     */
    public static function registerAnnotationDriver($driver)
    {
        static::instance()->addAnnotationDriver($driver);
    }

    /**
     * @param string $driver
     */
    protected function addAnnotationDriver($driver)
    {
        $this->checkDriver($driver);

        if (!isset($this->drivers['annotation'])) {
            $this->drivers['annotation'] = array();
        }

        $this->drivers['annotation'][] = $driver;
    }

    /**
     * @param string $driver
     */
    public static function registerYmlDriver($driver)
    {
        static::instance()->addYmlDriver($driver);
//
//        self::checkDriver($driver);
//        if (!isset(self::$drivers['yml'])) {
//            self::$drivers['yml'] = array();
//        }
//
//        self::$drivers['yml'][] = $driver;
    }

    /**
     * @param string $driver
     */
    protected function addYmlDriver($driver)
    {
        $this->checkDriver($driver);

        if (!isset($this->drivers['yml'])) {
            $this->drivers['yml'] = array();
        }

        $this->drivers['yml'][] = $driver;
    }

    /**
     * @param string $driver
     */
    public static function registerXmlDriver($driver)
    {
        static::instance()->addXmlDriver($driver);
//
//        self::checkDriver($driver);
//        if (!isset(self::$drivers['xml'])) {
//            self::$drivers['xml'] = array();
//        }
//
//        self::$drivers['xml'][] = $driver;
    }

    /**
     * @param string $driver
     */
    protected function addXmlDriver($driver)
    {
        $this->checkDriver($driver);

        if (!isset($this->drivers['xml'])) {
            $this->drivers['xml'] = array();
        }

        $this->drivers['xml'][] = $driver;
    }

    /**
     * @param string $driver
     */
    private function checkDriver($driver)
    {
        $reflector = new \ReflectionClass($driver);
        if (!$reflector->implementsInterface(DriverInterface::class)) {
            throw new \InvalidArgumentException(sprintf(
                'The object must be an instance of %s. Instance of %s given',
                DriverInterface::class,
                $driver
            ));
        }
    }

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
     * Create a driver from a Doctrine metadata driver.
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

            return new DriverChain($drivers);
        }

        if ($omDriver instanceof DoctrineAnnotationDriver) {
            return new MergeableDriver(
                $this->createAnnotationDriver($omDriver->getReader())
            );
        }

        if ($omDriver instanceof DoctrineFileDriver) {
            $reflClass = new \ReflectionClass($omDriver);

            $driverName = $reflClass->getShortName();
            if (strpos($driverName, 'Simplified') !== false) {
                $driverName = str_replace('Simplified', '', $driverName);
            }

            return new MergeableDriver(
                call_user_func(
                    array(static::class, 'create'.$driverName),
                    $omDriver->getLocator()
                )
            );
        }

        throw new \InvalidArgumentException('Cannot adapt Doctrine driver of class '.get_class($omDriver));
    }

    /**
     * @param AnnotationReader $reader
     *
     * @return array
     */
    protected function createAnnotationDriver(AnnotationReader $reader)
    {
        $drivers = array();
        foreach ($this->drivers['annotation'] as $driver) {
            $drivers[] = new $driver($reader);
        }

        return $drivers;
    }

    /**
     * @param FileLocator $locator
     *
     * @return array
     */
    protected function createYmlDriver(FileLocator $locator)
    {
        $drivers = array();
        foreach ($this->drivers['yml'] as $driver) {
            $drivers[] = new $driver($locator);
        }

        return $drivers;
    }

    /**
     * @param FileLocator $locator
     *
     * @return array
     */
    protected function createXmlDriver(FileLocator $locator)
    {
        $drivers = array();
        foreach ($this->drivers['xml'] as $driver) {
            $drivers[] = new $driver($locator);
        }

        return $drivers;
    }

    /**
     * @return DriverFactory
     */
    public static function instance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
