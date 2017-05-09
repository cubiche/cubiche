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

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Bus\Middlewares\Handler\MessageHandlerMiddleware;
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
        $this->ensureTypeOfMessage($message);

        try {
            $handler = $this->handlerClassResolver->resolve($message);
            $metadata = Validator::getMetadataForClass(get_class($message));
            $classMetadata = null;

            if ($metadata !== null) {
                /** @var ClassMetadata $classMetadata */
                $classMetadata = $metadata->getRootClassMetadata();
            }

            $handler($message, $classMetadata);
        } catch (NotFoundException $e) {
            // NotFoundException::handlerMethodNameForObject
            // NotFoundException::methodForObject
            if ($e->getCode() == 3 || $e->getCode() == 7) {
                throw $e;
            }
        }

        Validator::assert($message);

        return $next($message);
    }
}
