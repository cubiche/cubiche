<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Encoder;

use Cubiche\Domain\Localizable\LocalizableString;
use Cubiche\Domain\Localizable\LocalizableUrl;
use Cubiche\Domain\Localizable\LocalizableValueInterface;

/**
 * LocalizableValueObjectEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LocalizableValueObjectEncoder implements EncoderInterface
{
    /**
     * @var array
     */
    protected $aliases = array(
        'LocalizableString' => LocalizableString::class,
        'LocalizableUrl' => LocalizableUrl::class,
    );

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function supports($className)
    {
        try {
            $reflection = new \ReflectionClass($className);

            return $reflection->implementsInterface(LocalizableValueInterface::class);
        } catch (\ReflectionException $exception) {
            return isset($this->aliases[$className]);
        }
    }

    /**
     * @param LocalizableValueInterface $object
     *
     * @return mixed
     */
    public function encode($object)
    {
        if ($object !== null) {
            return $object->toArray();
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $className)
    {
        if ($data !== null) {
            try {
                new \ReflectionClass($className);
            } catch (\ReflectionException $exception) {
                if (isset($this->aliases[$className])) {
                    $className = $this->aliases[$className];
                }
            }

            return $className::fromArray($data);
        }

        return $data;
    }
}
