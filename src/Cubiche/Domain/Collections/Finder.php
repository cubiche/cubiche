<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

use Cubiche\Domain\Collections\Specification\SpecificationInterface;

/**
 * Finder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Finder implements FinderInterface
{
    /**
     * @var SpecificationInterface
     */
    protected $specification;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    protected $count;

    /**
     * @param SpecificationInterface $specification
     * @param int                    $offset
     * @param int                    $length
     */
    public function __construct(
        SpecificationInterface $specification,
        $offset = null,
        $length = null
    ) {
        $this->specification = $specification;
        $this->offset = $offset;
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        if ($this->count === null) {
            $this->count = $this->calculateCount();
        }

        return $this->count;
    }

    /**
     * @return int
     */
    abstract protected function calculateCount();
}
