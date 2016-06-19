<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\LazyCollection;

use Cubiche\Core\Collections\SetInterface;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Lazy Set.
 *
 * @method SetInterface collection()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class LazySet extends LazyCollection implements SetInterface
{
    /**
     * {@inheritdoc}
     */
    public function add($element)
    {
        $this->lazyInitialize();

        $this->collection()->add($element);
    }

    /**
     * {@inheritdoc}
     */
    public function addAll($elements)
    {
        $this->lazyInitialize();

        $this->collection()->addAll($elements);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($element)
    {
        $this->lazyInitialize();

        return $this->collection()->remove($element);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAll($elements)
    {
        $this->lazyInitialize();

        return $this->collection()->removeAll($elements);
    }

    /**
     * {@inheritdoc}
     */
    public function findOne(SpecificationInterface $criteria)
    {
        $this->lazyInitialize();

        return $this->collection()->findOne($criteria);
    }
}
