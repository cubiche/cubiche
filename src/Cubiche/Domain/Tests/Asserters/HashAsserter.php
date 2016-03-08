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

use mageekguy\atoum\asserters\hash as BaseHash;

/**
 * HashAsserter class.
 *
 * @method $this contains
 * @method $this isEqualTo
 * @method $this isEqualToContentsOfFile
 * @method $this isIdenticalTo
 * @method $this isMd5
 * @method $this isNotEqualTo
 * @method $this isNotIdenticalTo
 * @method $this isSha1
 * @method $this isSha256
 * @method $this isSha512
 * @method $this notContains
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class HashAsserter extends BaseHash
{
}
