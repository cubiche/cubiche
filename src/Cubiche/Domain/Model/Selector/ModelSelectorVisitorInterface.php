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
 * Model Selector Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ModelSelectorVisitorInterface extends SelectorVisitorInterface
{
    /**
     * @param Entity $entity
     *
     * @return mixed
     */
    public function visitEntity(Entity $entity);

    /**
     * @param Id $id
     *
     * @return mixed
     */
    public function visitId(Id $id);
}
