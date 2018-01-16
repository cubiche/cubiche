<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Fixtures\ReadModel;

use Cubiche\Domain\Model\AggregateRoot;
use Cubiche\Domain\Model\ReadModelInterface;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * PublishedPost class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PublishedPost extends AggregateRoot implements ReadModelInterface
{
    /**
     * @var string
     */
    protected $title;

    /**
     * Post constructor.
     *
     * @param PostId $id
     * @param string $title
     */
    public function __construct(PostId $id, $title)
    {
        parent::__construct($id);

        $this->title = $title;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return array(
            'id' => $this->id()->toNative(),
            'title' => $this->title,
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        return new self(PostId::fromNative($data['id']), $data['title']);
    }
}
