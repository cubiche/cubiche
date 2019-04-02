<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Async\Publisher\Policy;

use Cubiche\Core\Bus\MessageInterface;

/**
 * PublishPredefinedMessagesPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PublishPredefinedMessagesPolicy implements PublishPolicyInterface
{
    /**
     * @var array names
     */
    private $names;

    /**
     * PublishPredefinedMessagesPolicy constructor.
     *
     * @param array $names
     */
    public function __construct(array $names = [])
    {
        $this->names = $names;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldPublishMessage(MessageInterface $message)
    {
        if (in_array($message->messageName(), $this->names)) {
            return true;
        }

        return false;
    }
}
