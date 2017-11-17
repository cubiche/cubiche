<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Types;

use Cubiche\Domain\Web\EmailAddress;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types\NativeValueObjectType;

/**
 * EmailAddressType Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EmailAddressType extends NativeValueObjectType
{
    /**
     * {@inheritdoc}
     */
    public function targetClass()
    {
        return EmailAddress::class;
    }
}
