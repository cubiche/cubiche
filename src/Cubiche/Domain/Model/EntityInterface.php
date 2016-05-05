<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model;

use Cubiche\Core\Serializer\SerializableInterface;

/**
 * Entity Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface EntityInterface extends DomainObjectInterface, SerializableInterface
{
    /**
     * @return IdInterface
     */
    public function id();
}
