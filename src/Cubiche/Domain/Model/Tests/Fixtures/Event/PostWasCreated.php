<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Fixtures\Event;

use Cubiche\Domain\Model\EventSourcing\EntityDomainEvent;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * PostWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostWasCreated extends EntityDomainEvent
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $content;

    /**
     * PostWasCreated constructor.
     *
     * @param PostId $id
     * @param string $title
     * @param string $content
     */
    public function __construct(PostId $id, $title, $content)
    {
        parent::__construct($id);

        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return PostId
     */
    public function id()
    {
        return $this->aggregateId();
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }
}
