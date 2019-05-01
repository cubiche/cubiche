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
 * ShortNameFromClassResolver class.
 *
 * Examples:
 *  - Cubiche\Application\Command\ChangeTitleCommand => $fooTaskCommandHandler->changeTitle()
 *  - Cubiche\Application\Query\PostListQuery => $postListQueryHandler->postList()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ShortNameFromClassResolver extends NameFromClassResolver
{
    /**
     * @var array
     */
    private $suffixesToRemove;

    /**
     * @param array $suffixesToRemove The strings to remove from end of each class name
     */
    public function __construct($suffixesToRemove = ['Message', 'Event', 'Query', 'Command'])
    {
        $this->suffixesToRemove = $suffixesToRemove;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(MessageInterface $message): string
    {
        $methodName = parent::resolve($message);
        foreach ($this->suffixesToRemove as $suffixToRemove) {
            $suffixLength = strlen($suffixToRemove);
            if (substr($methodName, $suffixLength * -1) === $suffixToRemove) {
                return substr($methodName, 0, strlen($methodName) - $suffixLength);
            }
        }

        return $methodName;
    }
}
