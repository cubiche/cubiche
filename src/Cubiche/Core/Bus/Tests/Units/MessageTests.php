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

use Cubiche\Core\Bus\Message;
use Cubiche\Core\Bus\MessageId;
use Cubiche\Core\Bus\Tests\Fixtures\FooMessage;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;

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
            ->then()
                ->object($message->id())
                    ->isInstanceOf(MessageId::class)
        ;
    }

    /**
     * Test serialize/deserialize method.
     */
    public function testSerializeDeserialize()
    {
        $this
            ->given($message = new LoginUserMessage('ivan@cubiche.com'))
            ->then()
                ->variable($message->id())
                    ->isNull()
                ->and()
                ->when($data = $message->serialize())
                ->then()
                    ->object($message)
                        ->isEqualTo($newMessage = Message::deserialize($data))
                    ->object($newMessage->id())
                        ->isInstanceOf(MessageId::class)
        ;
    }
}
