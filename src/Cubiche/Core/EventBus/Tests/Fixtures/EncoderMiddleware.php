<?php
/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\EventBus\Tests\Fixtures;

use Cubiche\Core\EventBus\EventInterface;
use Cubiche\Core\EventBus\MiddlewareInterface;

/**
 * EncoderMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EncoderMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $algorithm;

    /**
     * EncoderMiddleware constructor.
     *
     * @param string $algorithm
     */
    public function __construct($algorithm = 'md5')
    {
        $this->algorithm = $algorithm;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(EventInterface $event, callable $next)
    {
        if ($event instanceof LoginUserEvent) {
            $event->setEmail(call_user_func($this->algorithm, $event->email()));
        }

        $next($event);
    }
}
