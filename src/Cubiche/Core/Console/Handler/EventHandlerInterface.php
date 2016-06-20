<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console\Handler;

use Webmozart\Console\Api\IO\IO;

/**
 * EventHandlerInterface class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface EventHandlerInterface
{
    /**
     * @param IO $io
     */
    public function setIo(IO $io);

    /**
     * @param callable $handler
     *
     * @return $this
     */
    public function addPreDispatchHandler($handler);

    /**
     * @param callable $handler
     *
     * @return $this
     */
    public function addPostDispatchHandler($handler);

    /**
     * @return $this
     */
    public function clearHandlers();
}
