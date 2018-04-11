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

use Cubiche\Core\Validator\Assertion;
use Cubiche\Core\Validator\Mapping\ClassMetadata;

/**
 * Post class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Post
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
     * @var string[]
     */
    protected $comments;

    /**
     * Post constructor.
     *
     * @param string $title
     * @param string $content
     */
    public function __construct($title = null, $content = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->comments = array(null, null);
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

    /**
     * @return string[]
     */
    public function comments()
    {
        return $this->comments;
    }

    /**
     * @param string $comment
     */
    public function addComment($comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($other)
    {
        return parent::equals($other) &&
            $this->title() == $other->title() &&
            $this->content() == $other->content()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public static function loadValidatorMetadata(ClassMetadata $classMetadata)
    {
        $classMetadata->addPropertyConstraint('title', Assertion::string()->notBlank());
        $classMetadata->addMethodConstraint('title', Assertion::string()->notBlank());
        $classMetadata->addPropertyConstraint('content', Assertion::string());

        $classMetadata->addPropertyConstraint('title', Assertion::integer()->notBlank(), 'foo');
        $classMetadata->addPropertyConstraint('content', Assertion::integer()->notBlank(), 'foo');
    }
}
