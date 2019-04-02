<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Handler;

use Cubiche\Core\Collections\ArrayCollection\SortedArraySet;
use Cubiche\Core\Comparable\Direction;
use Cubiche\Core\Comparable\SelectorComparator;
use Cubiche\Core\Serializer\Context\ContextInterface;

/**
 * HandlerManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HandlerManager implements HandlerManagerInterface
{
    /**
     * @var SortedArraySet|HandlerInterface[]
     */
    protected $handlers;

    /**
     * HandlerManager constructor.
     */
    public function __construct()
    {
        $this->handlers = new SortedArraySet([], new SelectorComparator(
            function (HandlerInterface $handler) {
                return $handler->order();
            },
            Direction::ASC()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function handler($typeName, ContextInterface $context)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($typeName, $context)) {
                return $handler;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers->add($handler);
    }
}
