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

use Cubiche\BoundedContext\Application\BoundedContext as BaseBoundedContext;
use Cubiche\MicroService\Application\Configuration\ServiceHelperTrait;
use Cubiche\MicroService\Application\Controllers\CommandController;
use Cubiche\MicroService\Application\Controllers\QueryController;
use Cubiche\MicroService\Application\Services\TokenContextInterface;
use Cubiche\MicroService\Application\Services\TokenManager;

/**
 * BoundedContext class.
 *
 * Sevice dependencies:
 *  - app.acl.token_manager
 *  - app.acl.token_context
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class BoundedContext extends BaseBoundedContext implements BoundedContextInterface
{
    use ServiceHelperTrait;

    /**
     * @param string $name
     *
     * @return CommandController
     */
    public function commandController($name)
    {
        return $this->get($this->getServiceAlias('command_controller', $name));
    }

    /**
     * @param string $name
     *
     * @return QueryController
     */
    public function queryController($name)
    {
        return $this->get($this->getServiceAlias('query_controller', $name));
    }

    /**
     * @return TokenManager
     */
    protected function tokenManager()
    {
        return $this->get('app.acl.token_manager');
    }

    /**
     * @return TokenContextInterface
     */
    protected function tokenContext()
    {
        return $this->get('app.acl.token_context');
    }
}
