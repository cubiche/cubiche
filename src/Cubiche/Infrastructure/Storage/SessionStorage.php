<?php

/*
 * This file is part of the Palmiche project.
 *
 * (c) Ivannis Suárez Jérez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Storage;

use Cubiche\Core\Storage\InvalidKeyException;
use Cubiche\Core\Storage\ReadException;
use Cubiche\Core\Storage\StorageInterface;
use Cubiche\Core\Storage\WriteException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * SessionStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SessionStorage implements StorageInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Sets the value for a key in the store.
     *
     * @param int|string $key
     * @param mixed      $value
     *
     * @throws WriteException      If the store cannot be written
     * @throws InvalidKeyException If the key is not a string or integer
     */
    public function set($key, $value)
    {
        $this->session->set($key, $value);
    }

    /**
     * Returns the value of key in store.
     *
     * If a key does not exist in the store, the default value passed in the
     * second parameter is returned.
     *
     * @param int|string $key
     * @param mixed      $default
     *
     * @return mixed
     *
     * @throws ReadException       If the store cannot be read
     * @throws InvalidKeyException If the key is not a string or integer
     */
    public function get($key, $default = null)
    {
        return $this->session->get($key, $default);
    }

    /**
     * Returns whether a key exists.
     *
     * @param int|string $key
     *
     * @return bool
     *
     * @throws ReadException       If the store cannot be read
     * @throws InvalidKeyException If the key is not a string or integer
     */
    public function has($key)
    {
        return $this->session->has($key);
    }

    /**
     * Removes a key from the store.
     *
     * @param int|string $key
     *
     * @return bool
     *
     * @throws WriteException      If the store cannot be written
     * @throws InvalidKeyException If the key is not a string or integer
     */
    public function remove($key)
    {
        return $this->session->remove($key) !== null;
    }

    /**
     * Removes all keys from the store.
     *
     * @throws WriteException If the store cannot be written
     */
    public function clear()
    {
        $this->session->clear();
    }

    /**
     * Returns all keys currently stored in the store.
     *
     * @return array
     *
     * @throws ReadException If the store cannot be read
     */
    public function keys()
    {
        return $this->session->all();
    }
}
