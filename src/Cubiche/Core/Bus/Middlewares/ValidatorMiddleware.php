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

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Validator\Validator;

/**
 * ValidatorMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ValidatorMiddleware extends MessageHandlerMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        try {
            $handler = $this->messageHandlerResolver->resolve($message);

            call_user_func($handler, $message, Validator::getMetadataForClass(get_class($message)));
        } catch (NotFoundException $e) {
        }

        Validator::assert($message);

        return $next($message);
    }
}
