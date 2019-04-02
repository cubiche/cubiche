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
 * CompositePublishMessagesPolicy class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CompositePublishMessagesPolicy implements PublishPolicyInterface
{
    /**
     * @var PublishPolicyInterface[]
     */
    protected $policies = [];

    /**
     * CompositePublishMessagesPolicy constructor.
     *
     * @param PublishPolicyInterface[] $policies
     */
    public function __construct(PublishPolicyInterface ...$policies)
    {
        $this->policies = $policies;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldPublishMessage(MessageInterface $message)
    {
        foreach ($this->policies as $policy) {
            if (!$policy->shouldPublishMessage($message)) {
                return false;
            }
        }

        return true;
    }
}
