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
 * BlogWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BlogWasCreated extends Event
{
    /**
     * @var string
     */
    protected $name;

    /**
     * BlogWasCreated constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
