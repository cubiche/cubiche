<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Factory;

use Cubiche\Core\Metadata\Driver\DriverInterface;
use Cubiche\Core\Metadata\Locator\FileLocatorInterface;
use Doctrine\Common\Annotations\Reader;

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
    public static function registerYamlDriver($driver)
    {
        static::instance()->addYamlDriver($driver);
    }

    /**
     * @param string $driver
     */
    protected function addYamlDriver($driver)
    {
        $this->checkDriver($driver);

        if (!isset($this->drivers['yaml'])) {
            $this->drivers['yaml'] = array();
        }

        $this->drivers['yaml'][] = $driver;
    }

    /**
     * @param string $driver
     */
    public static function registerXmlDriver($driver)
    {
        static::instance()->addXmlDriver($driver);
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
     * @param Reader $reader
     *
     * @return array
     */
    public function createAnnotationDriver(Reader $reader)
    {
        $drivers = array();
        foreach ($this->drivers['annotation'] as $driver) {
            $drivers[] = new $driver($reader);
        }

        return $drivers;
    }

    /**
     * @param FileLocatorInterface $locator
     *
     * @return array
     */
    public function createYamlDriver(FileLocatorInterface $locator)
    {
        $drivers = array();
        foreach ($this->drivers['yaml'] as $driver) {
            $drivers[] = new $driver($locator);
        }

        return $drivers;
    }

    /**
     * @param FileLocatorInterface $locator
     *
     * @return array
     */
    public function createXmlDriver(FileLocatorInterface $locator)
    {
        $drivers = array();
        foreach ($this->drivers['xml'] as $driver) {
            $drivers[] = new $driver($locator);
        }

        return $drivers;
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
