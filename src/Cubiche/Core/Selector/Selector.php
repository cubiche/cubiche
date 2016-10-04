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
     * @param callable|mixed $selector
     *
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public static function from($selector)
    {
        if ($selector instanceof SelectorInterface) {
            return $selector;
        }

        if (\is_callable($selector)) {
            return new Callback($selector);
        }

        return new Value($selector);
    }

    /**
     * {@inheritdoc}
     */
    public function select($selector)
    {
        return new Composite($this, self::from($selector));
    }

    /**
     * {@inheritdoc}
     */
    protected function innerCallback()
    {
        return array($this, 'apply');
    }
}
