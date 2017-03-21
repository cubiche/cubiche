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
     * @var array
     */
    protected static $namespaces = array();

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

    /**
     * @param string $namespace
     * @param bool   $prepend
     */
    public static function registerValidator($namespace, $prepend = false)
    {
        if (!isset(static::$namespaces[$namespace])) {
            static::$namespaces[$namespace] = $prepend;
        }
    }

    /**
     * @param string $ruleName
     * @param array  $arguments
     *
     * @return Validator
     */
    public static function __callStatic($ruleName, $arguments)
    {
        foreach (static::$namespaces as $namespace => $prepend) {
            static::with($namespace, $prepend);
        }

        return parent::__callStatic($ruleName, $arguments);
    }
}
