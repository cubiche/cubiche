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
 * Where Projector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class WhereProjector implements ProjectorInterface
{
    /**
     * @var SpecificationInterface
     */
    protected $criteria;

    /**
     * @var ProjectorInterface
     */
    protected $projector;

    /**
     * @param SpecificationInterface $criteria
     * @param ProjectorInterface     $projector
     */
    public function __construct(SpecificationInterface $criteria, ProjectorInterface $projector)
    {
        $this->criteria = $criteria;
        $this->projector = $projector;
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        /** @var \Cubiche\Core\Projection\ProjectionWrapperInterface $item */
        foreach ($this->projector()->project($value) as $item) {
            if ($this->criteria()->evaluate($item->projection())) {
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
     * @return \Cubiche\Core\Projection\ProjectorInterface
     */
    public function projector()
    {
        return $this->projector;
    }
}
