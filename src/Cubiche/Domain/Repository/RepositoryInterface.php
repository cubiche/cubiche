<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Repository;

use Cubiche\Core\Collections\CollectionInterface;

/**
 * Repository Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface RepositoryInterface extends CollectionInterface
{
    /**
     * @param mixed $item
     */
    public function update($item);

    /**
     * @param mixed $id
     *
     * @return mixed
     */
    public function get($id);
}
