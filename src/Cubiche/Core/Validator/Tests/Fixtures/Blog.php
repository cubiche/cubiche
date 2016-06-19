<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Validator\Tests\Fixtures;

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * Blog class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Blog
{
    /**
     * @var array
     */
    protected $posts;

    /**
     * Blog constructor.
     *
     * @param array $posts
     */
    public function __construct(array $posts = array())
    {
        $this->posts = $posts;
    }

    /**
     * @return array
     */
    public function posts()
    {
        return $this->posts;
    }
}
