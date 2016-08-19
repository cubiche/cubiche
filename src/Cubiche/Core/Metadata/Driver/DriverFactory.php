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

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\FileLocatorInterface;

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
     * @param AbstractYamlDriver $driver
     */
    public static function registerYamlDriver(AbstractYamlDriver $driver)
    {
        static::instance()->addYamlDriver($driver);
    }

    /**
     * @param AbstractYamlDriver $driver
     */
    protected function addYamlDriver(AbstractYamlDriver $driver)
    {
        if (!isset($this->drivers['yaml'])) {
            $this->drivers['yaml'] = array();
        }

        $this->drivers['yaml'][] = $driver;
    }

    /**
     * @param AbstractXmlDriver $driver
     */
    public static function registerXmlDriver(AbstractXmlDriver $driver)
    {
        static::instance()->addXmlDriver($driver);
    }

    /**
     * @param AbstractXmlDriver $driver
     */
    protected function addXmlDriver(AbstractXmlDriver $driver)
    {
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
    protected function createAnnotationDriver(Reader $reader)
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
    protected function createYamlDriver(FileLocatorInterface $locator)
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
    protected function createXmlDriver(FileLocatorInterface $locator)
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
