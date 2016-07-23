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

use Cubiche\Core\Delegate\Delegate;
use Cubiche\Core\Hashable\HashCoderInterface;

/**
 * Custom Equality Comparer class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CustomEqualityComparer extends AbstractEqualityComparer
{
    /**
     * @var Delegate
     */
    protected $equalityComparerDelegate;

    /**
     * @param callable           $equalityComparer
     * @param HashCoderInterface $hashCoder
     */
    public function __construct(callable $equalityComparer, HashCoderInterface $hashCoder = null)
    {
        parent::__construct($hashCoder);

        $this->delegate = new Delegate($equalityComparer);
    }

    /**
     * {@inheritdoc}
     */
    public function equals($a, $b)
    {
        return $this->equalityComparerDelegate->__invoke($a, $b);
    }
}
