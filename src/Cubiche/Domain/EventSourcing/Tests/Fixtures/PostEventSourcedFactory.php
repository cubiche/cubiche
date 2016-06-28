<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Tests\Fixtures;

use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * PostEventSourcedFactory class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostEventSourcedFactory
{
    /**
     * @param string $title
     * @param string $content
     *
     * @return PostEventSourced
     */
    public static function create($title, $content)
    {
        return new PostEventSourced(
            PostId::fromNative(md5(rand())),
            $title,
            $content
        );
    }
}
