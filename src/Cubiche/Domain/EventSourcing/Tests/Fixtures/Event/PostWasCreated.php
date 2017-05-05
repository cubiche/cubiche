<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Fixtures\Event;

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Mapping\ClassMetadata;
use Cubiche\Domain\EventSourcing\DomainEvent;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * PostWasCreated class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostWasCreated extends DomainEvent
{
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

        $this->setPayload('title', $title);
        $this->setPayload('content', $content);
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
        return $this->getPayload('title');
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->getPayload('content');
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addMethodConstraint('title', Assert::stringType()->notBlank());
        $classMetadata->addMethodConstraint('content', Assert::stringType());
    }
}
