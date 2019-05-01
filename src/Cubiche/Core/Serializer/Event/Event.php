<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Event;

use Cubiche\Core\Bus\NamedMessageInterface;
use Cubiche\Core\EventBus\Event\Event as BaseEvent;
use Cubiche\Core\Serializer\Context\ContextInterface;

/**
 * Event class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class Event extends BaseEvent implements NamedMessageInterface
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
