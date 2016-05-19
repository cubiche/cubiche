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
use Cubiche\Domain\Model\Tests\Fixtures\Event\PostTitleWasChanged;
use Cubiche\Domain\Model\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\Model\Tests\Fixtures\Event\PostWasPublished;
use Cubiche\Domain\Model\Tests\Fixtures\Validator\PostValidator;

/**
 * Tag class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Tag extends AggregateRoot
{
    /**
     * @return Tag
     */
    public static function create()
    {
        return new self(PostId::fromNative(md5(rand())));
    }
}
