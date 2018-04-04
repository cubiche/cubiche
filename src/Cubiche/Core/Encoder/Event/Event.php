<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder\Event;

use Cubiche\Core\Encoder\Context\ContextInterface;
use Cubiche\Core\EventBus\Event\Event as BaseEvent;

/**
 * Event class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Event extends BaseEvent
{
    /**
     * @var ContextInterface
     */
    protected $context;

    /**
     * @var object
     */
    protected $object;

    /**
     * @var array
     */
    protected $type;

    /**
     * Event constructor.
     *
     * @param ContextInterface $context
     * @param object           $object
     * @param array            $type
     */
    public function __construct(ContextInterface $context, $object, array $type)
    {
        $this->context = $context;
        $this->object = $object;
        $this->type = $type;
    }

    /**
     * @return ContextInterface
     */
    public function context()
    {
        return $this->context;
    }

    /**
     * @return object
     */
    public function object()
    {
        return $this->object;
    }

    /**
     * @return array
     */
    public function type()
    {
        return $this->type;
    }
}
