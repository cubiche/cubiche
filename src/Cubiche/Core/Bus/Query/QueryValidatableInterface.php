<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Query;

use Cubiche\Core\Bus\MessageValidatableInterface;

/**
 * QueryValidatable interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface QueryValidatableInterface extends QueryInterface, MessageValidatableInterface
{
}
