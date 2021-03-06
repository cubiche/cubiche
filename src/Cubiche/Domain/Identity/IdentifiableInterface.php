<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Identity;

use Cubiche\Domain\Model\IdInterface;

/**
 * Identifiable interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface IdentifiableInterface
{
    /**
     * @return IdInterface
     */
    public function id();

    /**
     * @param IdInterface $id
     *
     * @return mixed
     */
    public function setId(IdInterface $id);
}
