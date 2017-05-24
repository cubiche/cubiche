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
 * MethodMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodMetadata implements \Serializable
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var \ReflectionMethod
     */
    protected $reflection;

    /**
     * @var ArrayHashMapInterface
     */
    protected $metadata;

    /**
     * MethodMetadata constructor.
     *
     * @param string $className
     * @param string $methodName
     */
    public function __construct($className, $methodName)
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->metadata = new ArrayHashMap();

        $this->reflection = new \ReflectionMethod($className, $methodName);
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
    public function methodName()
    {
        return $this->methodName;
    }

    /**
     * @return \ReflectionMethod
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
     * @param array  $args
     *
     * @return mixed
     */
    public function invoke($obj, array $args = array())
    {
        return $this->reflection->invokeArgs($obj, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->className,
            $this->methodName,
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
            $this->methodName,
            $metadata) = unserialize($str);

        $this->reflection = new \ReflectionMethod($this->className, $this->methodName);
        $this->reflection->setAccessible(true);

        $this->metadata = new ArrayHashMap($metadata);
    }
}
