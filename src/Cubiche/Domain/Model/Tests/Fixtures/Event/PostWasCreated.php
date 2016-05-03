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
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * PostWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostWasCreated extends EntityDomainEvent
{
    /**
     * @var StringLiteral
     */
    protected $title;

    /**
     * @var StringLiteral
     */
    protected $content;

    /**
     * PostWasCreated constructor.
     *
     * @param IdInterface   $id
     * @param StringLiteral $title
     * @param StringLiteral $content
     */
    public function __construct(IdInterface $id, StringLiteral $title, StringLiteral $content)
    {
        parent::__construct($id);

        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return IdInterface
     */
    public function id()
    {
        return $this->aggregateId();
    }

    /**
     * @return StringLiteral
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return StringLiteral
     */
    public function content()
    {
        return $this->content;
    }
}
