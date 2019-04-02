<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Recorder;

use Cubiche\Core\Bus\MessageInterface;

/**
 * MessageRecorder interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MessageRecorderInterface
{
    /**
     * @return MessageInterface[]
     */
    public function recordedMessages();

    /**
     * Clear recorded messages.
     */
    public function clearMessages();
}
