<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Async\Middlewares\Publisher;

use Cubiche\Core\Bus\Async\Publisher\Policy\PublishPolicyInterface;
use Cubiche\Core\Bus\Middlewares\MiddlewareInterface;
use Cubiche\Core\Bus\Async\Publisher\MessagePublisherInterface;

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
     * @var PublishPolicyInterface
     */
    protected $publishPolicy;

    /**
     * MessagePublisherMiddleware constructor.
     *
     * @param MessagePublisherInterface $publisher
     * @param PublishPolicyInterface    $publishPolicy
     */
    public function __construct(MessagePublisherInterface $publisher, PublishPolicyInterface $publishPolicy)
    {
        $this->publisher = $publisher;
        $this->publishPolicy = $publishPolicy;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        if ($this->publishPolicy->shouldPublishMessage($message)) {
            $this->publisher->publishMessage($message);
        }

        return $next($message);
    }
}
