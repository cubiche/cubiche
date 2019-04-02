<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\Configuration;

use Cubiche\BoundedContext\Infrastructure\Configuration\CoreConfigurator as BaseCoreConfigurator;

/**
 * CoreConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class CoreConfigurator extends BaseCoreConfigurator
{
    /**
     * @param array $config
     *
     * @return array
     */
    protected function configurators(array $config)
    {
        return array_merge(
            parent::configurators($config),
            [new TokenContextConfigurator()]
        );
    }
}
