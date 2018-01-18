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

use Cubiche\Domain\Localizable\LocaleCode;
use Cubiche\Domain\Model\NativeValueObjectInterface;
use Cubiche\Domain\System\Decimal;
use Cubiche\Domain\System\DecimalInfinite;
use Cubiche\Domain\System\Enum;
use Cubiche\Domain\System\Integer;
use Cubiche\Domain\System\Real;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\EmailAddress;
use Cubiche\Domain\Web\Host;
use Cubiche\Domain\Web\HostName;
use Cubiche\Domain\Web\IPAddress;
use Cubiche\Domain\Web\Path;
use Cubiche\Domain\Web\Port;
use Cubiche\Domain\Web\Url;

/**
 * ValueObjectEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ValueObjectEncoder implements EncoderInterface
{
    /**
     * @var array
     */
    protected $aliases = array(
        'Decimal' => Decimal::class,
        'DecimalInfinite' => DecimalInfinite::class,
        'Enum' => Enum::class,
        'Integer' => Integer::class,
        'Real' => Real::class,
        'StringLiteral' => StringLiteral::class,
        'EmailAddress' => EmailAddress::class,
        'Host' => Host::class,
        'HostName' => HostName::class,
        'IPAddress' => IPAddress::class,
        'Path' => Path::class,
        'Port' => Port::class,
        'Url' => Url::class,
        'LocaleCode' => LocaleCode::class,
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

            return $reflection->implementsInterface(NativeValueObjectInterface::class);
        } catch (\ReflectionException $exception) {
            return isset($this->aliases[$className]);
        }
    }

    /**
     * @param NativeValueObjectInterface $object
     *
     * @return mixed
     */
    public function encode($object)
    {
        if ($object !== null) {
            return $object->toNative();
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

            return $className::fromNative($data);
        }

        return $data;
    }
}
