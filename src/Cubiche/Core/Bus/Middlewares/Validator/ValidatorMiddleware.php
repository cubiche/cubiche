<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Validator;

use Cubiche\Core\Bus\Middlewares\MiddlewareInterface;
use Cubiche\Core\Validator\Validator;

/**
 * ValidatorMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ValidatorMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        Validator::assert($message);

        $next($message);
    }
}
