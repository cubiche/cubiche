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

use mageekguy\atoum\asserters\castToString as BaseCastToString;

/**
 * CastToStringAsserter class.
 *
 * @method $this contains
 * @method $this notContains
 * @method $this hasLength
 * @method $this hasLengthGreaterThan
 * @method $this hasLengthLessThan
 * @method $this isEmpty
 * @method $this isEqualTo
 * @method $this isEqualToContentsOfFile
 * @method $this isIdenticalTo
 * @method $this isNotEmpty
 * @method $this isNotEqualTo
 * @method $this isNotIdenticalTo
 * @method $this matches
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CastToStringAsserter extends BaseCastToString
{
}
