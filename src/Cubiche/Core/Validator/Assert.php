<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Validator;

use Respect\Validation\Validator as Constraints;

/**
 * Assert class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Assert extends Constraints
{
    /**
     * @var string
     */
    const DEFAULT_GROUP = 'Default';

    /**
     * Create a constraints instance.
     *
     * @return Constraints
     */
    public static function create()
    {
        return new static(func_get_args());
    }
}
