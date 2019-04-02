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

use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * LocalizableValue class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class LocalizableValue extends AbstractInputObjectType
{
    /**
     * {@inheritdoc}
     */
    public function build($config)
    {
        $config
            ->addField('localeCode', new NonNullType(new StringType()))
            ->addField('translation', new NonNullType(new StringType()))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function parseValue($value)
    {
        $value = parent::parseValue($value);

        return array($value['localeCode'] => $value['translation']);
    }
}
