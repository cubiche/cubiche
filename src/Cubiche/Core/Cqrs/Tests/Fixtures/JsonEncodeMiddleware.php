<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Cqrs\Tests\Fixtures;

use Cubiche\Core\Bus\Middlewares\MiddlewareInterface;

/**
 * JsonEncodeMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class JsonEncodeMiddleware implements MiddlewareInterface
{
    /**
     * @param mixed    $message
     * @param callable $next
     *
     * @return mixed
     */
    public function handle($message, callable $next)
    {
        return $next(json_encode($message));
    }
}
