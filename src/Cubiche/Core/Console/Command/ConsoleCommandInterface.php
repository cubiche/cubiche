<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console\Command;

use Cubiche\Core\Bus\Command\CommandInterface;
use Webmozart\Console\Api\IO\IO;

/**
 * ConsoleCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ConsoleCommandInterface extends CommandInterface
{
    /**
     * @return IO
     */
    public function getIo();

    /**
     * @param IO $io
     */
    public function setIo(IO $io);
}
