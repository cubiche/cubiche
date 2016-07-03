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

use Cubiche\Core\Visitor\Visitee;

/**
 * Abstract Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Selector extends Visitee implements SelectorInterface
{
    /**
     * @param callable $selector
     *
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public static function from(callable $selector)
    {
        if ($selector instanceof SelectorInterface) {
            return $selector;
        }

        return new Custom($selector);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return \call_user_func_array(array($this, 'apply'), \func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function select(callable $selector)
    {
        return new Composite($this, self::from($selector));
    }
}
