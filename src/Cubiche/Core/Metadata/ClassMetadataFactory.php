<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata;

use Cubiche\Core\Metadata\Cache\CacheInterface;
use Cubiche\Core\Metadata\Driver\DriverInterface;

/**
 * ClassMetadataFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassMetadataFactory implements ClassMetadataFactoryInterface
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var array
     */
    protected $loadedMetadata = array();

    /**
     * ClassMetadataFactory constructor.
     *
     * @param DriverInterface     $driver
     * @param CacheInterface|null $cache
     */
    public function __construct(DriverInterface $driver, CacheInterface $cache = null)
    {
        $this->driver = $driver;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllMetadata()
    {
        $metadatas = [];
        foreach ($this->driver->getAllClassNames() as $className) {
            if (null !== $metadata = $this->getMetadataFor($className)) {
                $metadatas[] = $metadata;
            }
        }

        return $metadatas;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($className)
    {
        if (isset($this->loadedMetadata[$className])) {
            return $this->loadedMetadata[$className];
        }

        if ($this->cache !== null) {
            if (($cached = $this->cache->load($className)) !== null) {
                $this->loadedMetadata[$className] = $cached;
            } else {
                foreach ($this->loadMetadata($className) as $loadedClassName) {
                    $this->cache->save($this->loadedMetadata[$loadedClassName]);
                }
            }
        } else {
            $this->loadMetadata($className);
        }

        if (isset($this->loadedMetadata[$className])) {
            return $this->loadedMetadata[$className];
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($className)
    {
        return isset($this->loadedMetadata[$className]);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadataFor($className, ClassMetadataInterface $metadata)
    {
        $this->loadedMetadata[$className] = $metadata;
    }

    /**
     * Loads the metadata of the class in question and all it's ancestors whose metadata
     * is still not loaded.
     *
     * @param string $className
     *
     * @return array
     */
    protected function loadMetadata($className)
    {
        $classNames = [];
        foreach ($this->getClassHierarchy($className) as $class) {
            if (null !== $classMetadata = $this->driver->loadMetadataForClass($class)) {
                $this->loadedMetadata[$class] = $classMetadata;
                $classNames[] = $class;
            }
        }

        return $classNames;
    }

    /**
     * @param string $className
     *
     * @return array
     */
    private function getClassHierarchy($className)
    {
        $classes = array();
        $refl = new \ReflectionClass($className);

        do {
            $classes[] = $refl->getName();
            $refl = $refl->getParentClass();
        } while (false !== $refl);

        return array_reverse($classes, false);
    }
}
