<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Handler\MethodName;

use Cubiche\Core\Bus\MessageInterface;

/**
 * ShortNameFromClassWithSuffixResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\ChangeTitleCommand => $fooTaskCommandHandler->changeTitleHandler()
 *  - Cubiche\Application\Query\PostListQuery => $postListQueryHandler->postListHandler()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ShortNameFromClassWithSuffixResolver extends ShortNameFromClassResolver
{
    /**
     * @var string
     */
    private $suffixToAdd;

    /**
     * @param string $suffixToAdd      The string to add to the end of each class name
     * @param array  $suffixesToRemove The strings to remove from end of each class name
     */
    public function __construct($suffixToAdd = 'Handler', $suffixesToRemove = ['Message', 'Event', 'Query', 'Command'])
    {
        parent::__construct($suffixesToRemove);

        $this->suffixToAdd = $suffixToAdd;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message): string
    {
        return parent::resolve($message) . $this->suffixToAdd;

        $methodName = parent::resolve(get_class($message));
        if (substr($methodName, $this->suffixLength * -1) === $this->suffixToRemove) {
            $methodName = substr($methodName, 0, strlen($methodName) - $this->suffixLength);
        }

        return $methodName.$this->suffixToAdd;
    }
}
