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
 * MessageRecorder trait.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait MessageRecorder
{
    /**
     * @var MessageInterface[]
     */
    protected $messages = [];

    /**
     * {@inheritdoc}
     */
    protected function recordMessage(MessageInterface $message)
    {
        $this->messages[] = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function recordedMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function clearMessages()
    {
        $this->messages = [];
    }
}
