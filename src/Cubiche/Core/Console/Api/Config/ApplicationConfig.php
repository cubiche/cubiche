<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Console\Api\Config;

use Webmozart\Console\Api\Config\ApplicationConfig as BaseApplicationConfig;

/**
 * ApplicationConfig class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ApplicationConfig extends BaseApplicationConfig
{
    /**
     * Starts a configuration block for a command.
     *
     * The configuration of the command is returned by this method. You can use
     * the fluent interface to configure the sub-command before jumping back to
     * this configuration with {@link CommandConfig::end()}:
     *
     * ```php
     * protected function configure()
     * {
     *     $this
     *         ->setName('server')
     *         ->setDescription('List and manage servers')
     *
     *         ->beginCommand('add')
     *             ->setDescription('Add a server')
     *             ->addArgument('host', Argument::REQUIRED)
     *             ->addOption('port', 'p', Option::VALUE_OPTIONAL, null, 80)
     *         ->end()
     *
     *         // ...
     *     ;
     * }
     * ```
     *
     * @param string $name The name of the command.
     *
     * @return CommandConfig The command configuration.
     *
     * @see editCommand()
     */
    public function beginCommand($name)
    {
        $commandConfig = new CommandConfig($name, $this);

        $this->addCommandConfig($commandConfig);

        return $commandConfig;
    }
}
