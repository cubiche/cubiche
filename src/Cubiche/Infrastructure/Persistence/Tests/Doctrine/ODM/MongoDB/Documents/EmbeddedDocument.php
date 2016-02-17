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
 * Embedded Document Class.
 *
 * @ODM\EmbeddedDocument
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EmbeddedDocument
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
     * @return int
     */
    public function intValue()
    {
        return $this->intValue;
    }
}
