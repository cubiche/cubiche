<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Locator;

use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\Bus\Exception\NotFoundException;

/**
 * InMemoryLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryLocator implements LocatorInterface
{
    /**
     * @var ArrayCollection
     */
    protected $handlers;

    /**
     * InMemoryLocator constructor.
     *
     * @param array $classNameToHandlerMap
     */
    public function __construct(array $classNameToHandlerMap = [])
    {
        $this->handlers = new ArrayCollection();
        foreach ($classNameToHandlerMap as $className => $handler) {
            $this->addHandler($className, $handler);
        }
    }

    /**
     * @param string $className
     * @param object $handler
     */
    public function addHandler($className, $handler)
    {
        if (!is_string($className)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an string as a command/query name. Instance of %s given',
                is_object($className) ? get_class($className) : gettype($className)
            ));
        }

        if (!is_object($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an object as handler. Instance of %s given',
                gettype($handler)
            ));
        }

        $this->handlers->set($className, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function locate($className)
    {
        if (!$this->handlers->containsKey($className)) {
            throw NotFoundException::handlerFor($className);
        }

        return $this->handlers->get($className);
    }
}
