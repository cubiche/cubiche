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

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Document Class.
 *
 * @ODM\Document(collection="documents")
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Document
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

    public function __construct($textValue, $intValue)
    {
        $this->textValue = $textValue;
        $this->intValue = $intValue;
    }

    /**
     * @return int
     */
    public function intValue()
    {
        return $this->intValue;
    }

    /**
     * @return \Cubiche\Infrastructure\Persistence\Tests\Doctrine\ODM\MongoDB\Documents\EmbeddedDocument
     */
    public function embedded()
    {
        return $this->embedded;
    }
}
