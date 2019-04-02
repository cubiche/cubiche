<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\GraphQL\Type;

use Youshido\GraphQL\Type\ListType\AbstractListType;

/**
 * LocalizableType class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LocalizableType extends AbstractListType
{
    /**
     * {@inheritdoc}
     */
    public function getItemType()
    {
        return new LocalizableValue();
    }

    /**
     * {@inheritdoc}
     */
    public function parseValue($value)
    {
        $result = array();
        foreach ((array) $value as $keyValue => $valueItem) {
            $result = array_merge($result, $this->getItemType()->parseValue($valueItem));
        }

        return $result;
    }
}
