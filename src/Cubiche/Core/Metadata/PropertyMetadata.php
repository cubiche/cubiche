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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\ArrayHashMapInterface;

/**
 * PropertyMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertyMetadata implements \Serializable
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var \ReflectionProperty
     */
    protected $reflection;

    /**
     * @var ArrayHashMapInterface
     */
    protected $metadata;

    public function __construct($className, $propertyName)
    {
        $this->className = $className;
        $this->propertyName = $propertyName;
        $this->metadata = new ArrayHashMap();

        $this->reflection = new \ReflectionProperty($className, $propertyName);
        $this->reflection->setAccessible(true);
    }

    /**
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function propertyName()
    {
        return $this->propertyName;
    }

    /**
     * @return \ReflectionProperty
     */
    public function reflection()
    {
        return $this->reflection;
    }

    /**
     * @return array
     */
    public function metadata()
    {
        return $this->metadata->toArray();
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function addMetadata($key, $value)
    {
        $this->metadata->set($key, $value);
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getMetadata($key)
    {
        return $this->metadata->get($key);
    }

    /**
     * @param object $obj
     *
     * @return mixed
     */
    public function getValue($obj)
    {
        return $this->reflection->getValue($obj);
    }

    /**
     * @param object $obj
     * @param string $value
     */
    public function setValue($obj, $value)
    {
        $this->reflection->setValue($obj, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->className,
            $this->propertyName,
            $this->metadata->toArray(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->className,
            $this->propertyName,
            $metadata) = unserialize($str);

        $this->reflection = new \ReflectionProperty($this->className, $this->propertyName);
        $this->reflection->setAccessible(true);

        $this->metadata = new ArrayHashMap($metadata);
    }
}
