<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types;

/**
 * Dynamic Type Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait DynamicTypeTrait
{
    /**
     * @var string
     */
    protected $targetClass = '';

    /**
     * @return string
     */
    public function targetClass()
    {
        return $this->targetClass;
    }

    /**
     * @param string $targetClass
     */
    public function setTargetClass($targetClass)
    {
        $this->targetClass = $targetClass;
    }
}
