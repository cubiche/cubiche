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

use Cubiche\Core\Delegate\AbstractCallable;
use Cubiche\Core\Visitor\VisiteeTrait;

/**
 * Abstract Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Selector extends AbstractCallable implements SelectorInterface
{
    use VisiteeTrait;

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
    public function select(callable $selector)
    {
        return new Composite($this, self::from($selector));
    }

    /**
     * {@inheritdoc}
     */
    protected function innerCallable()
    {
        return array($this, 'apply');
    }
}
