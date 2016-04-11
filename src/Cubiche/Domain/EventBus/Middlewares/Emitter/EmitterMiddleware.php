<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventBus\Middlewares\Handler;

use Cubiche\Domain\EventBus\Emitter;
use Cubiche\Domain\EventBus\EventInterface;
use Cubiche\Domain\EventBus\MiddlewareInterface;

/**
 * EmitterMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EmitterMiddleware implements MiddlewareInterface
{
    /**
     * @var Emitter
     */
    protected $emitter;

    /**
     * EmitterMiddleware constructor.
     *
     * @param Emitter $emitter
     */
    public function __construct(Emitter $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(EventInterface $event, callable $next)
    {
        $this->emitter->emit($event);

        $next($event);
    }

    /**
     * @return Emitter
     */
    public function emitter()
    {
        return $this->emitter;
    }
}
