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

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\ArrayHashMapInterface;
use Doctrine\ODM\MongoDB\Types\HashType;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Array HashMap Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayHashMapType extends HashType
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
        if ($value !== null && $value instanceof ArrayHashMapInterface) {
            $items = array();
            $type = Type::getType($this->innerType);
            foreach ($value as $key => $item) {
                $items[$key] = $type->convertToDatabaseValue($item);
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
            return new ArrayHashMap();
        }
        if (is_array($value) || $value instanceof \Traversable) {
            $items = array();
            $type = Type::getType($this->innerType);
            foreach ($value as $key => $item) {
                $items[$key] = $type->convertToPHPValue($item);
            }

            return new ArrayHashMap($items);
        }

        return parent::convertToPHPValue($value);
    }
}
