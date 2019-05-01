<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Message\Publisher;

use Cubiche\Core\Bus\MessageInterface;
use Cubiche\Core\Bus\Message\Recorder\MessageRecorderInterface;

/**
 * MessagePublisher interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MessagePublisherInterface extends MessageRecorderInterface
{
    /**
     * @param MessageInterface $message
     */
    public function recordMessage(MessageInterface $message);

    /**
     * @param MessageInterface $message
     */
    public function publishMessage(MessageInterface $message);
}
