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

use Cubiche\Domain\System\Real;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types\NativeValueObjectType;

/**
 * RealType Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RealType extends NativeValueObjectType
{
    /**
     * {@inheritdoc}
     */
    public function targetClass()
    {
        return Real::class;
    }
}
