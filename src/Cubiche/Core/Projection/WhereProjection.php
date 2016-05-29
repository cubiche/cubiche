<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection;

use Cubiche\Core\Specification\SpecificationInterface;

/**
 * Where Projection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class WhereProjection implements ProjectionInterface
{
    /**
     * @var SpecificationInterface
     */
    protected $criteria;

    /**
     * @var ProjectionInterface
     */
    protected $projection;

    /**
     * @param SpecificationInterface $criteria
     * @param ProjectionInterface    $projection
     */
    public function __construct(SpecificationInterface $criteria, ProjectionInterface $projection)
    {
        $this->criteria = $criteria;
        $this->projection = $projection;
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        foreach ($this->projection()->project($value) as $item) {
            if ($this->criteria->evaluate($item)) {
                yield $item;
            }
        }
    }

    /**
     * @return \Cubiche\Core\Specification\SpecificationInterface
     */
    public function criteria()
    {
        return $this->criteria;
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectionInterface
     */
    public function projection()
    {
        return $this->projection;
    }
}
