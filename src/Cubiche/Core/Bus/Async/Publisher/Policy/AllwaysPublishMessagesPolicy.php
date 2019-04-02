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
 * AllwaysPublishMessagesPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AllwaysPublishMessagesPolicy implements PublishPolicyInterface
{
    /**
     * {@inheritdoc}
     */
    public function shouldPublishMessage(MessageInterface $message)
    {
        return true;
    }
}
