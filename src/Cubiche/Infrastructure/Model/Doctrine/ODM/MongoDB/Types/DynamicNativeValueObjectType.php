<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types;

/**
 * Dynamic Native Value Object Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class DynamicNativeValueObjectType extends NativeValueObjectType
{
    use DynamicTypeTrait;
}
