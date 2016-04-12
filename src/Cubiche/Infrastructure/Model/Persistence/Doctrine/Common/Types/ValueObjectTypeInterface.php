<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Model\Persistence\Doctrine\Common\Types;

use Cubiche\Domain\Model\ValueObjectInterface;

/**
 * Value Object Type Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ValueObjectTypeInterface
{
    /**
     * @param ValueObjectInterface $value
     *
     * @return mixed
     */
    public function toDatabaseValue(ValueObjectInterface $value);

    /**
     * @param mixed $value
     *
     * @return Cubiche\Domain\Model\ValueObjectInterface
     */
    public function fromDatabaseValue($value);
}
