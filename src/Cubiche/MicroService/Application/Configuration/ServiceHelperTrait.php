<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Configuration;

/**
 * ServiceHelper trait.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait ServiceHelperTrait
{
    /**
     * @param string $name
     *
     * @return string
     */
    protected function commandControllerAlias($name)
    {
        return $this->getServiceAlias('command_controller', $name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function queryControllerAlias($name)
    {
        return $this->getServiceAlias('query_controller', $name);
    }
}
