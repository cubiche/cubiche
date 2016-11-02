<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Console\Tests\Fixtures\Event;

use Cubiche\Core\EventBus\Event\Event;

/**
 * PostTitleWasChanged class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostTitleWasChanged extends Event
{
    /**
     * @var string
     */
    protected $title;

    /**
     * PostTitleWasChanged constructor.
     *
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }
}
