<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Metadata;

use Cubiche\Core\Metadata\ClassMetadata as BaseClassMetadata;

/**
 * ClassMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassMetadata extends BaseClassMetadata
{
    /**
     * @var bool
     */
    public $isMigratable = false;

    /**
     * @param bool $isMigratable
     */
    public function setIsMigratable($isMigratable)
    {
        $this->isMigratable = (bool) $isMigratable;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->name,
            $this->methodMetadata,
            $this->propertyMetadata,
            $this->fileResources,
            $this->createdAt,
            $this->isMigratable,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->name,
            $this->methodMetadata,
            $this->propertyMetadata,
            $this->fileResources,
            $this->createdAt,
            $this->isMigratable) = unserialize($str);

        $this->reflection = new \ReflectionClass($this->name);
    }
}
