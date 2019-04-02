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
 * PublishPolicy interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface PublishPolicyInterface
{
    /**
     * @param MessageInterface $message
     *
     * @return bool
     */
    public function shouldPublishMessage(MessageInterface $message);
}
