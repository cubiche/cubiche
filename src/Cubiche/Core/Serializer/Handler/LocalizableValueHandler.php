<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Handler;

use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Visitor\DeserializationVisitor;
use Cubiche\Core\Serializer\Visitor\SerializationVisitor;
use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Localizable\LocalizableUrl;
use Cubiche\Domain\Localizable\LocalizableValueInterface;

/**
 * LocalizableValueHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LocalizableValueHandler implements HandlerSubscriberInterface
{
    /**
     * @param SerializationVisitor      $visitor
     * @param LocalizableValueInterface $localizable
     * @param array                     $type
     * @param ContextInterface          $context
     *
     * @return mixed
     */
    public function serialize(
        SerializationVisitor $visitor,
        LocalizableValueInterface $localizable,
        array $type,
        ContextInterface $context
    ) {
        return $visitor->visitArray($localizable->toArray(), $type, $context);
    }

    /**
     * @param DeserializationVisitor $visitor
     * @param array                  $data
     * @param array                  $type
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function deserialize(DeserializationVisitor $visitor, array $data, array $type, ContextInterface $context)
    {
        return $type['name']::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedHandlers()
    {
        return array(
            'serializers' => array(
                LocalizableString::class => 'serialize',
                LocalizableUrl::class => 'serialize',
            ),
            'deserializers' => array(
                LocalizableString::class => 'deserialize',
                LocalizableUrl::class => 'deserialize',
            ),
        );
    }
}
