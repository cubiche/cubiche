<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Tests\Doctrine\ODM\MongoDB\Documents;

use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Collections\CollectionInterface;
use Cubiche\Domain\Model\AggregateRoot;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Document Class.
 *
 * @ODM\Document(collection="documents")
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Document extends AggregateRoot
{
    /**
     * @var mixed
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $textValue;

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    protected $intValue;

    /**
     * @var EmbeddedDocument
     * @ODM\EmbedOne(targetDocument="EmbeddedDocument")
     */
    protected $embedded;

    /**
     * @var CollectionInterface
     * @ODM\EmbedMany(targetDocument="EmbeddedDocument")
     */
    protected $embeddeds;

    /**
     * @param string $textValue
     * @param int    $intValue
     */
    public function __construct($textValue, $intValue)
    {
        $this->textValue = $textValue;
        $this->intValue = $intValue;
        $this->embeddeds = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function intValue()
    {
        return $this->intValue;
    }

    /**
     * @return string
     */
    public function textValue()
    {
        return $this->textValue;
    }

    /**
     * @return \Cubiche\Infrastructure\Persistence\Tests\Doctrine\ODM\MongoDB\Documents\EmbeddedDocument
     */
    public function embedded()
    {
        return $this->embedded;
    }

    /**
     * @return \Cubiche\Domain\Collections\CollectionInterface
     */
    public function embeddeds()
    {
        return $this->embeddeds;
    }
}
