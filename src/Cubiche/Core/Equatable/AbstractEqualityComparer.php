<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Equatable;

use Cubiche\Core\Delegate\AbstractCallable;
use Cubiche\Core\Hashable\HashCoderInterface;
use Cubiche\Core\Hashable\HashCoder;

/**
 * Abstract Equality Comparer class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class AbstractEqualityComparer extends AbstractCallable implements EqualityComparerInterface
{
    /**
     * @var HashCoderInterface
     */
    private $hashCoder;

    /**
     * @param HashCoderInterface $hashCoder
     */
    public function __construct(HashCoderInterface $hashCoder = null)
    {
        $this->hashCoder = $hashCoder;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($a, $b)
    {
        if ($a instanceof EquatableInterface) {
            return $a->equals($b);
        }

        if ($b instanceof EquatableInterface) {
            return $b->equals($a);
        }

        return $a === $b;
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode($value)
    {
        return $this->hashCoder()->hashCode($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function hashCoder()
    {
        return $this->hashCoder !== null ? $this->hashCoder : ($this->hashCoder = HashCoder::defaultHashCoder());
    }

    /**
     * {@inheritdoc}
     */
    protected function innerCallback()
    {
        return array($this, 'equals');
    }
}
