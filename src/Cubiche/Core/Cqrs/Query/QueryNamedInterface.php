<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Cqrs\Query;

use Cubiche\Core\Bus\MessageNamedInterface;

/**
 * QueryNamed interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface QueryNamedInterface extends QueryInterface, MessageNamedInterface
{
}
