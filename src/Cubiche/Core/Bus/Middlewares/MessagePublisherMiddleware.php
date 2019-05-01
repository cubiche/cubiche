<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares;

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Message\Publisher\MessagePublisherInterface;

/**
 * MessagePublisherMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessagePublisherMiddleware implements MiddlewareInterface
{
    /**
     * @var MessagePublisherInterface
     */
    protected $publisher;

    /**
     * MessagePublisherMiddleware constructor.
     *
     * @param MessagePublisherInterface $publisher
     */
    public function __construct(MessagePublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        // get all the recorded messages
        $recordedMessages = $this->publisher->recordedMessages();
        if (count($recordedMessages) > 0) {

            // clear recorded messages
            $this->publisher->clearMessages();

            // publish all the recorded messages
            foreach ($recordedMessages as $recordedMessage) {
                $this->publisher->publishMessage($recordedMessage);
            }
        }

        return $next($message);
    }
}
