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

use Cubiche\Core\Console\Tests\Fixtures\BlogId;
use Cubiche\Domain\EventSourcing\DomainEvent;

/**
 * BlogWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class BlogWasCreated extends DomainEvent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * BlogWasCreated constructor.
     *
     * @param BlogId $id
     * @param string $name
     */
    public function __construct(BlogId $id, $name)
    {
        parent::__construct($id);

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
