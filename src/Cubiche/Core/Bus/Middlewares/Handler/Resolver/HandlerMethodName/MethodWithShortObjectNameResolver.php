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
 * MethodWithShortObjectNameResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\ChangeTitleCommand => $fooTaskCommandHandler->changeTitle()
 *  - Cubiche\Application\Query\PostListQuery => $postListQueryHandler->postList()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodWithShortObjectNameResolver extends MethodWithObjectNameResolver
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
    public function __construct($suffix = 'Message')
    {
        $this->suffix = $suffix;
        $this->suffixLength = strlen($suffix);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($className)
    {
        $methodName = parent::resolve($className);
        if (substr($methodName, $this->suffixLength * -1) !== $this->suffix) {
            return $methodName;
        }

        return substr($methodName, 0, strlen($methodName) - $this->suffixLength);
    }
}
