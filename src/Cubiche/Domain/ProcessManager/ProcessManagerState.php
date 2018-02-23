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

use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * ProcessManagerState class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class ProcessManagerState extends Entity implements ReadModelInterface
{
    /**
     * @var StringLiteral
     */
    protected $state;

    /**
     * ProcessManagerState constructor.
     *
     * @param IdInterface   $id
     * @param StringLiteral $initialState
     */
    public function __construct(IdInterface $id, StringLiteral $initialState)
    {
        parent::__construct($id);

        $this->state = $initialState;
    }

    /**
     * @return StringLiteral
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = StringLiteral::fromNative($state);
    }
}
