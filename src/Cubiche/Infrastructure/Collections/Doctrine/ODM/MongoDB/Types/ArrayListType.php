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

use Cubiche\Core\Collection\ArrayCollection\ArrayList;
use Cubiche\Core\Collection\ArrayCollection\ArrayListInterface;
use Doctrine\ODM\MongoDB\Types\CollectionType;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Array List Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayListType extends CollectionType
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
        if ($value !== null && $value instanceof ArrayListInterface) {
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
            return new ArrayList();
        }
        if (is_array($value) || $value instanceof \Traversable) {
            $items = array();
            $type = Type::getType($this->innerType);
            foreach ($value as $item) {
                $items[] = $type->convertToPHPValue($item);
            }

            return new ArrayList($items);
        }

        return parent::convertToPHPValue($value);
    }
}
