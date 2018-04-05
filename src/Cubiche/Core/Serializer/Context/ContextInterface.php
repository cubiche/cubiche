<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Context;

use Cubiche\Core\Serializer\Visitor\VisitorInterface;
use Cubiche\Core\Serializer\Visitor\VisitorNavigatorInterface;

/**
 * Context interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ContextInterface
{
    /**
     * @return VisitorInterface
     */
    public function visitor();

    /**
     * @return VisitorNavigatorInterface
     */
    public function navigator();
}
