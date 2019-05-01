<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Handler\Locator;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;

/**
 * InMemoryLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryLocator implements HandlerLocatorInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $handlers;

    /**
     * InMemoryLocator constructor.
     *
     * @param array $messagesHandlerMap
     */
    public function __construct(array $messagesHandlerMap = [])
    {
        $this->handlers = new ArrayHashMap();
        foreach ($messagesHandlerMap as $nameOfMessage => $handler) {
            $this->addHandler($nameOfMessage, $handler);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler(string $nameOfMessage, $handler)
    {
        $this->handlers->set($nameOfMessage, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function locate(string $nameOfMessage)
    {
        if (!$this->handlers->containsKey($nameOfMessage)) {
            throw NotFoundException::handlerFor($nameOfMessage);
        }

        return $this->handlers->get($nameOfMessage);
    }
}
