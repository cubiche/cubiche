<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application;

use Cubiche\BoundedContext\Application\BoundedContextInterface as BaseBoundedContextInterface;
use Cubiche\MicroService\Application\Controllers\CommandController;
use Cubiche\MicroService\Application\Controllers\QueryController;

/**
 * BoundedContext interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface BoundedContextInterface extends BaseBoundedContextInterface
{
    /**
     * @param string $name
     *
     * @return CommandController
     */
    public function commandController($name);

    /**
     * @param string $name
     *
     * @return QueryController
     */
    public function queryController($name);
}
