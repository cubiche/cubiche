<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units;

use Cubiche\Core\Bus\MessageId;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;

/**
 * MessageTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MessageTests extends TestCase
{
    /**
     * Test create method.
     */
    public function testMessageWithIdentifier()
    {
        $this
            ->given($message = new FooMessage())
            ->and($messageId = MessageId::next())
            ->then()
                ->object($message->messageId())
                    ->isInstanceOf(MessageId::class)
                ->and()
                ->when($message->setMessageId($messageId))
                ->then()
                    ->object($message->messageId())
                        ->isEqualTo($messageId)
        ;
    }
}
