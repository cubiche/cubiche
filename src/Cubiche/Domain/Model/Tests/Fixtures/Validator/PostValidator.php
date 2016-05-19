<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Fixtures\Validator;

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Validator;

/**
 * PostValidator class.
 *
 * @method static PostValidator create()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PostValidator extends Validator
{
    /**
     * @return $this
     */
    public function title()
    {
        return $this->addConstraint(
            Assert::stringType()->notBlank()
        );
    }

    /**
     * @return $this
     */
    public function content()
    {
        return $this->addConstraint(
            Assert::stringType()
        );
    }
}
