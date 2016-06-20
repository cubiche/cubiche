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

use Webmozart\Console\Api\Config\CommandConfig as BaseCommandConfig;

/**
 * CommandConfig class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CommandConfig extends BaseCommandConfig
{
    use CommandConfigTrait;

    /**
     * Starts a configuration block for a sub-command.
     *
     * A sub-command is executed if the name of the command is passed after the
     * name of the containing command. For example, if the command "server" has
     * a sub-command command named "add", that command can be called with:
     *
     * ```
     * $ console server add ...
     * ```
     *
     * The configuration of the sub-command is returned by this method. You can
     * use the fluent interface to configure the sub-command before jumping back
     * to this configuration with {@link SubCommandConfig::end()}:
     *
     * ```php
     * protected function configure()
     * {
     *     $this
     *         ->beginCommand('server')
     *             ->setDescription('List and manage servers')
     *
     *             ->beginSubCommand('add')
     *                 ->setDescription('Add a server')
     *                 ->addArgument('host', Argument::REQUIRED)
     *                 ->addOption('port', 'p', Option::VALUE_OPTIONAL, null, 80)
     *             ->end()
     *         ->end()
     *
     *         // ...
     *     ;
     * }
     * ```
     *
     * @param string $name The name of the sub-command.
     *
     * @return SubCommandConfig The sub-command configuration.
     *
     * @see editSubCommand()
     */
    public function beginSubCommand($name)
    {
        $config = new SubCommandConfig($name, $this);

        $this->addSubCommandConfig($config);

        return $config;
    }
}
