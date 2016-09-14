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

use Webmozart\Console\Api\IO\IO;

/**
 * ConsoleCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ConsoleCommand implements ConsoleCommandInterface
{
    /**
     * @var IO
     */
    protected $io;

    /**
     * {@inheritdoc}
     */
    public function getIo()
    {
        return $this->io;
    }

    /**
     * {@inheritdoc}
     */
    public function setIo(IO $io)
    {
        $this->io = $io;
    }
}
