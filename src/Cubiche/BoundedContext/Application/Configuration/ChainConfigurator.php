<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application\Configuration;

use Cubiche\Core\Collections\ArrayCollection\ArrayList;

/**
 * ChainConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ChainConfigurator implements ConfiguratorInterface
{
    /**
     * @var ConfiguratorInterface[]|ArrayList
     */
    private $configurators;

    /**
     * ChainConfigurator constructor.
     *
     * @param ConfiguratorInterface[] $configurators
     */
    public function __construct(array $configurators)
    {
        $this->configurators = new ArrayList($configurators);
    }

    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        $configuration = [];
        foreach ($this->configurators as $configurator) {
            $configuration = array_merge($configuration, $configurator->configuration());
        }

        return $configuration;
    }
}
