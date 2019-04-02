<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\GraphQL\Config\Object;

use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\TypeService;

/**
 * MapTypeConfig class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class MapTypeConfig extends ObjectTypeConfig
{
    /**
     * {@inheritdoc}
     */
    public function getRules()
    {
        return [
            'keyType' => ['type' => TypeService::TYPE_GRAPHQL_TYPE, 'final' => true],
            'valueType' => ['type' => TypeService::TYPE_GRAPHQL_TYPE, 'final' => true],
            'resolve' => ['type' => TypeService::TYPE_CALLABLE],
            'args' => ['type' => TypeService::TYPE_ARRAY_OF_INPUT_FIELDS],
        ];
    }
}
