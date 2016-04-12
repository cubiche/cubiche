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

use Cubiche\Core\Selector\SelectorVisitorInterface;

/**
 * Model Selector Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait ModelSelectorTrait
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\This::acceptSelectorVisitor()
     */
    public function acceptSelectorVisitor(SelectorVisitorInterface $visitor)
    {
        if ($visitor instanceof ModelSelectorVisitorInterface) {
            return $this->acceptModelSelectorVisitor($visitor);
        }

        return parent::acceptSelectorVisitor($visitor);
    }
}
