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

use Cubiche\Core\Selector\Method;

/**
 * Id Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Id extends Method implements ModelSelectorInterface
{
    use ModelSelectorTrait;

    public function __construct()
    {
        parent::__construct('id');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Model\Selector\ModelSelectorInterface::acceptModelSelectorVisitor()
     */
    public function acceptModelSelectorVisitor(ModelSelectorVisitorInterface $visitor)
    {
        return $visitor->visitId($this);
    }
}
