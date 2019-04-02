<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Publisher;

use Cubiche\Core\Bus\BusInterface;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Recorder\MessageRecorder;

/**
 * MessagePublisher class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessagePublisher implements MessagePublisherInterface
{
    use MessageRecorder { recordMessage as public; }

    /**
     * @var BusInterface
     */
    protected $bus;

    /**
     * MessagePublisher constructor.
     *
     * @param BusInterface $bus
     */
    public function __construct(BusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    public function publishMessage(MessageInterface $message)
    {
        $this->bus->dispatch($message);
    }
}
