<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\ProcessManager;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collections\ArrayCollection\ArraySet;

/**
 * ProcessManagerConfig class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ProcessManagerConfig
{
    /**
     * @var ArraySet
     */
    protected $states;

    /**
     * @var ArrayHashMap
     */
    protected $transitions;

    /**
     * ProcessManagerConfig constructor.
     */
    public function __construct()
    {
        $this->states = new ArraySet();
        $this->transitions = new ArrayHashMap();
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function addState($state)
    {
        $this->states->add($state);

        return $this;
    }

    /**
     * @param array $states
     *
     * @return $this
     */
    public function addStates(array $states)
    {
        foreach ($states as $state) {
            $this->addState($state);
        }

        return $this;
    }

    /**
     * @param string $transitionName
     * @param array  $fromStates
     * @param string $toState
     *
     * @return $this
     */
    public function addTransition($transitionName, array $fromStates, $toState)
    {
        $this->transitions->set($transitionName, array(
            'from' => $fromStates,
            'to' => $toState,
        ));

        return $this;
    }

    /**
     * @param array $transitions
     *
     * @return $this
     */
    public function addTransitions(array $transitions)
    {
        foreach ($transitions as $transitionName => $transition) {
            $this->addTransition($transitionName, $transition['from'], $transition['to']);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'states' => $this->states->toArray(),
            'transitions' => $this->transitions->toArray(),
            'callbacks' => array(),
        );
    }
}
