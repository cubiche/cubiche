<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Fixtures\Message;

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Exception\ValidationException;
use Cubiche\Core\Validator\Validator;

/**
 * UserMessageValidator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class UserMessageValidator
{
    /**
     * @param LoginUserMessage $event
     *
     * @return bool
     */
    public function loginUserValidator(LoginUserMessage $event)
    {
        Validator::assert($event->email(), Assert::email()->contains('gmail.com'));
    }

    /**
     * @param LogoutUserMessage $event
     *
     * @return bool
     */
    public function logoutUserValidator(LogoutUserMessage $event)
    {
        throw new ValidationException('The email is invalid');
    }
}
