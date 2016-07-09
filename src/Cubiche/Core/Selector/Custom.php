<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

use Cubiche\Core\Delegate\Delegate;

/**
 * Custom Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Custom extends Selector
{
    /**
     * @var Delegate
     */
    protected $delegate;

    /**
     * @param callable $selector
     */
    public function __construct(callable $selector)
    {
        $this->delegate = new Delegate($selector);
    }

    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return $this->delegate->__invoke($value);
    }
}
