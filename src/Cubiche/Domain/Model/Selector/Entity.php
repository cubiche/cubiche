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

use Cubiche\Core\Selector\This;

/**
 * Entity Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Entity extends This implements ModelSelectorInterface
{
    use ModelSelectorTrait;

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function id()
    {
        return $this->select(new Id());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\Selector\ModelSelectorInterface::acceptModelSelectorVisitor()
     */
    public function acceptModelSelectorVisitor(ModelSelectorVisitorInterface $visitor)
    {
        return $visitor->visitEntity($this);
    }
}
