<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Selector;

use Cubiche\Core\Selector\SelectorInterface;

/**
 * Model Selector Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ModelSelectorInterface extends SelectorInterface
{
    /**
     * @param ModelSelectorVisitorInterface $visitor
     *
     * @return mixed
     */
    public function acceptModelSelectorVisitor(ModelSelectorVisitorInterface $visitor);
}
