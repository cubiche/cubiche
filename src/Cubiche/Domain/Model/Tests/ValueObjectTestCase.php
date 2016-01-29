<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model\Tests;

use Cubiche\Domain\Model\ValueObjectInterface;

/**
 * Value Object Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class ValueObjectTestCase extends TestCase
{
    /**
     * @return ValueObjectInterface
     */
    abstract protected function firstValue();

    /**
     * @return ValueObjectInterface
     */
    abstract protected function secondValue();

    /**
     * @param ValueObjectInterface $a
     */
    public function equalsIsReflexive(ValueObjectInterface $a)
    {
        $this->assertTrue($a->equals($a));
    }

    /**
     * @param ValueObjectInterface $a
     * @param ValueObjectInterface $b
     */
    public function equalsIsSymmetric(ValueObjectInterface $a, ValueObjectInterface $b)
    {
        if ($a->equals($b)) {
            $this->assertTrue($b->equals($a));
        } else {
            $this->assertFalse($b->equals($a));
        }
    }

    /**
     * @param ValueObjectInterface $a
     * @param ValueObjectInterface $b
     * @param ValueObjectInterface $c
     */
    public function equalsIsTransitive(ValueObjectInterface $a, ValueObjectInterface $b, ValueObjectInterface $c)
    {
        if ($a->equals($b) && $b->equals($c)) {
            $this->assertTrue($a->equals($c));
        }
    }

    /**
     * @test
     */
    public function equals()
    {
        $this->equalsIsReflexive($this->firstValue());
        $this->equalsIsReflexive($this->secondValue());

        $this->equalsIsSymmetric($this->firstValue(), $this->firstValue());
        $this->equalsIsSymmetric($this->firstValue(), $this->secondValue());

        $this->equalsIsTransitive($this->firstValue(), $this->firstValue(), $this->firstValue());
        $this->equalsIsTransitive($this->secondValue(), $this->secondValue(), $this->secondValue());
    }
}
