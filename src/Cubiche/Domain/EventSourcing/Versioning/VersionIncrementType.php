<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Versioning;

use Cubiche\Core\Enum\Enum;

/**
 * VersionIncrementType class.
 *
 * @method static VersionIncrementType MAJOR()
 * @method static VersionIncrementType MINOR()
 * @method static VersionIncrementType PATCH()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class VersionIncrementType extends Enum
{
    const MAJOR = 'major';
    const MINOR = 'minor';
    const PATCH = 'patch';
}
