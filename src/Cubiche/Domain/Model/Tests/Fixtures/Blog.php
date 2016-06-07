<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Fixtures;

use Cubiche\Domain\Model\AggregateRoot;

/**
 * Blog class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Blog extends AggregateRoot
{
    /**
     * @return Blog
     */
    public static function create()
    {
        return new self(PostId::fromNative(md5(rand())));
    }
}
