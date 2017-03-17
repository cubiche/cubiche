<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Middlewares\Handler\Resolver\HandlerMethodName;

/**
 * MethodWithShortObjectNameAndSuffixResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\ChangeTitleCommand => $fooTaskCommandHandler->changeTitleHandler()
 *  - Cubiche\Application\Query\PostListQuery => $postListQueryHandler->postListHandler()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodWithShortObjectNameAndSuffixResolver extends MethodWithObjectNameResolver
{
    /**
     * @var string
     */
    private $suffixToRemove;

    /**
     * @var string
     */
    private $suffixToAdd;

    /**
     * @var int
     */
    private $suffixLength;

    /**
     * @param string $suffixToRemove The string to remove from end of each class name
     * @param string $suffixToAdd    The string to add to the end of each class name
     */
    public function __construct($suffixToRemove = 'Message', $suffixToAdd = 'Handler')
    {
        $this->suffixToRemove = $suffixToRemove;
        $this->suffixToAdd = $suffixToAdd;
        $this->suffixLength = strlen($suffixToRemove);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($className)
    {
        $methodName = parent::resolve($className);
        if (substr($methodName, $this->suffixLength * -1) !== $this->suffixToRemove) {
            throw new \Exception(sprintf('Invalid suffix %s', $this->suffixToRemove));
        }

        return substr($methodName, 0, strlen($methodName) - $this->suffixLength).$this->suffixToAdd;
    }
}
