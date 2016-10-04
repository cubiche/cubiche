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

use Cubiche\Core\Delegate\CallableInterface;
use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Selector Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SelectorInterface extends CallableInterface, VisiteeInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function apply($value);

    /**
     * @param callable|mixed $selector
     *
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function select($selector);
}
