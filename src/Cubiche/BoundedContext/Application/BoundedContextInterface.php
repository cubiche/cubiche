<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application;

use Cubiche\BoundedContext\Application\Configuration\ConfiguratorInterface;
use DI\NotFoundException;

/**
 * BoundedContext interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
interface BoundedContextInterface extends ConfiguratorInterface
{
    /**
     * Gets a parameter.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws NotFoundException if the parameter is not defined
     */
    public function getParameter($name);

    /**
     * Checks if a parameter exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasParameter($name);

    /**
     * Gets the bounded context namespace.
     *
     * @return string
     */
    public function getNamespace();
}
