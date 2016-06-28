<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Storage;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Serializer\SerializerInterface;

/**
 * InMemoryStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryStorage extends AbstractStorage implements StorageInterface
{
    /**
     * @var HashMap
     */
    protected $store;

    /**
     * Creates a new store.
     *
     * @param SerializerInterface $serializer
     * @param array               $elements
     */
    public function __construct(SerializerInterface $serializer, array $elements = array())
    {
        parent::__construct($serializer);

        $this->store = new ArrayHashMap();
        foreach ($elements as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->validateKey($key);

        $this->store->set($key, $this->serializer->serialize($value));
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $this->validateKey($key);
        if (!$this->store->containsKey($key)) {
            return $default;
        }

        return $this->serializer->deserialize($this->store->get($key));
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        $this->validateKey($key);

        return $this->store->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $this->validateKey($key);

        $removed = $this->store->removeAt($key);
        if ($removed !== null) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->store->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return array_keys($this->store->toArray());
    }
}
