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

use Cubiche\Core\Selector\SelectorInterface;

/**
 * For Each Projector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ForEachProjector implements ProjectorInterface
{
    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @var ProjectorInterface
     */
    protected $projector;

    /**
     * @param SelectorInterface  $selector
     * @param ProjectorInterface $projector
     */
    public function __construct(SelectorInterface $selector, ProjectorInterface $projector)
    {
        $this->selector = $selector;
        $this->projector = $projector;
    }

    /**
     * {@inheritdoc}
     */
    public function project($value)
    {
        $values = $this->selector()->apply($value);
        if (!\is_array($values) && !$value instanceof \Traversable) {
            $values = array($values);
        }

        foreach ($values as $value) {
            //yield from $this->projection()->project($value); in PHP 7
            foreach ($this->projector()->project($value) as $item) {
                yield $item;
            }
        }
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * @return \Cubiche\Core\Projection\ProjectorInterface
     */
    public function projector()
    {
        return $this->projector;
    }
}
