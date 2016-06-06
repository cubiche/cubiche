<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Types\DynamicTypeTrait;

/**
 * DynamicEnumType class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DynamicEnumType extends EnumType
{
    use DynamicTypeTrait;
}
