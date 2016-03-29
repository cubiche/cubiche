<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Command\Middlewares\Handler\Resolver\MethodName;

/**
 * MethodWithShortCommandNameResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\FooTaskCommand => $handler->handleFooTask()
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
    public function resolve($command)
    {
        $methodName = parent::resolve($command);
        if (substr($methodName, $this->suffixLength * -1) !== $this->suffix) {
            return $methodName;
        }

        return substr($methodName, 0, strlen($methodName) - $this->suffixLength);
    }
}
