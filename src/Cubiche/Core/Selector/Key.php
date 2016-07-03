<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

/**
 * Key Selector Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Key extends Field
{
    /**
     * {@inheritdoc}
     */
    public function apply($value)
    {
        return \is_array($value) && isset($value[$this->name]) ? $value[$this->name] : null;
    }
}
