<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Types;

use Cubiche\Core\Collections\ArrayCollection;
use Cubiche\Core\Collections\ArrayCollectionInterface;
use Doctrine\ODM\MongoDB\Types\CollectionType;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Array Collection Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ArrayCollectionType extends CollectionType
{
    /**
     * @var string
     */
    protected $innerType = '';

    /**
     * @return string
     */
    protected function innerType()
    {
        return $this->innerType;
    }

    /**
     * @param string $innerType
     */
    public function setInnerType($innerType)
    {
        $this->innerType = $innerType;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        if ($value !== null && $value instanceof ArrayCollectionInterface) {
            $items = array();
            $type = Type::getType($this->innerType);
            foreach ($value as $item) {
                $items[] = $type->convertToDatabaseValue($item);
            }
            $value = $items;
        }

        return parent::convertToDatabaseValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        if ($value === null) {
            return new ArrayCollection();
        }
        if (is_array($value) || $value instanceof \Traversable) {
            $items = array();
            $type = Type::getType($this->innerType);
            foreach ($value as $item) {
                $items[] = $type->convertToPHPValue($item);
            }

            return new ArrayCollection($items);
        }

        return parent::convertToPHPValue($value);
    }
}
