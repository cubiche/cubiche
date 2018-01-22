<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\EventStore;

use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * StreamName class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class StreamName
{
    /**
     * @var IdInterface
     */
    protected $id;

    /**
     * @var StringLiteral
     */
    protected $category;

    /**
     * @var StringLiteral
     */
    protected $name;

    public function __construct(IdInterface $id, StringLiteral $category)
    {
        $this->id = $id;
        $this->category = $category;
        $this->name = StringLiteral::fromNative(sprintf('%s-%s', $category->toNative(), $id->toNative()));
    }

    /**
     * @return IdInterface
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return StringLiteral
     */
    public function category()
    {
        return $this->category;
    }

    /**
     * @return StringLiteral
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name->toNative();
    }
}
