<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Tests\Asserters;

use mageekguy\atoum\asserters\error as BaseError;

/**
 * ErrorAsserter class.
 *
 * @method $this exists
 * @method $this notExists
 * @method $this withType
 * @method $this withAnyType
 * @method $this withMessage
 * @method $this withAnyMessage
 * @method $this withPattern
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ErrorAsserter extends BaseError
{
}
