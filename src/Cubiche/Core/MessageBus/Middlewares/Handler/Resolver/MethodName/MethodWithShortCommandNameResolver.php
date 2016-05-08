<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\MessageBus\Middlewares\Handler\Resolver\MethodName;

use Cubiche\Core\MessageBus\Command\CommandInterface;

/**
 * MethodWithShortCommandNameResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\FooTaskCommand => $handler->fooTask()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodWithShortCommandNameResolver extends MethodWithCommandNameResolver
{
    /**
     * @var string
     */
    private $suffix;

    /**
     * @var int
     */
    private $suffixLength;

    /**
     * @param string $suffix The string to remove from end of each class name
     */
    public function __construct($suffix = 'Command')
    {
        $this->suffix = $suffix;
        $this->suffixLength = strlen($suffix);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(CommandInterface $command)
    {
        $methodName = parent::resolve($command);
        if (substr($methodName, $this->suffixLength * -1) !== $this->suffix) {
            return $methodName;
        }

        return substr($methodName, 0, strlen($methodName) - $this->suffixLength);
    }
}
