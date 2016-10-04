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
 * Callback Equality Comparer class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CallbackEqualityComparer extends AbstractEqualityComparer
{
    /**
     * @var Delegate
     */
    protected $callbackDelegate;

    /**
     * @param callable           $callback
     * @param HashCoderInterface $hashCoder
     */
    public function __construct(callable $callback, HashCoderInterface $hashCoder = null)
    {
        parent::__construct($hashCoder);

        $this->callbackDelegate = new Delegate($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function equals($a, $b)
    {
        return $this->callbackDelegate->__invoke($a, $b);
    }
}
