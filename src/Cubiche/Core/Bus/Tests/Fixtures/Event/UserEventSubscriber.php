<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Tests\Fixtures\Event;

use Cubiche\Core\Bus\Event\Event;
use Cubiche\Core\Bus\Event\EventSubscriberInterface;

/**
 * UserEventSubscriber class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class UserEventSubscriber implements EventSubscriberInterface
{
    const FOO_EVENT = 'event.foo';
    const BAR_EVENT = 'event.bar';
    const USER_LOGIN = 'user.login';

    /**
     * @param LoginUserEvent $event
     *
     * @return bool
     */
    public function onLogin(LoginUserEvent $event)
    {
        $event->setEmail('login@cubiche.org');
    }

    /**
     * @param LoginUserEvent $event
     *
     * @return bool
     */
    public function onLoginSuccess(LoginUserEvent $event)
    {
        $event->setEmail('success@cubiche.org');
    }

    /**
     * @param Event $event
     *
     * @return bool
     */
    public function onFoo(Event $event)
    {
    }

    /**
     * @param Event $event
     *
     * @return bool
     */
    public function onBar(Event $event)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            self::FOO_EVENT => 'onFoo',
            self::BAR_EVENT => array('onBar', 21),
            self::USER_LOGIN => array(
                array('onLogin', 100), array('onLoginSuccess', 50),
            ),
        );
    }
}
