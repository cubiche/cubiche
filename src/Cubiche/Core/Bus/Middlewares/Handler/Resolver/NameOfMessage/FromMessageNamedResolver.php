<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfMessage;

use Cubiche\Core\Bus\MessageInterface;

/**
 * FromMessageNamedResolver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class FromMessageNamedResolver implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message)
    {
        $type = $this->getType();
        if ($message instanceof $type) {
            return $this->getName($message);
        }

        throw new \InvalidArgumentException(sprintf(
            'The object of type %s should implement the %s interface',
            get_class($message),
            $type
        ));
    }

    /**
     * @return mixed
     */
    abstract protected function getType();

    /**
     * @param MessageInterface $message
     *
     * @return string
     */
    abstract protected function getName(MessageInterface $message);
}
