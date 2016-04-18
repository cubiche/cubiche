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

use Cubiche\Core\Visitor\VisiteeInterface;

/**
 * Selector Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SelectorInterface extends VisiteeInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function apply($value);

    /**
     * @param SelectorInterface $selector
     *
     * @return SelectorInterface $selector
     */
    public function select(SelectorInterface $selector);

    /**
     * @param SelectorVisitorInterface $visitor
     *
     * @return mixed
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor);
}
