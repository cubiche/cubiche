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

use Cubiche\Core\Bus\Command\CommandInterface;

/**
 * CommandHandlerMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandHandlerMiddleware extends MessageHandlerMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        if (!$message instanceof CommandInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The object must be an instance of %s. Instance of %s given',
                    CommandInterface::class,
                    get_class($message)
                )
            );
        }

        parent::handle($message, $next);
    }
}
