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

use DateTime;
use DateTimeImmutable;

/**
 * DateTimeEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DateTimeEncoder implements EncoderInterface
{
    /**
     * @var string ISO-8601 UTC date/time format
     */
    const DATETIME_FORMAT = "Y-m-d\TH:i:s.u\Z";

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function supports($className)
    {
        return $className == 'DateTime' || $className == 'DateTimeImmutable';
    }

    /**
     * @param NativeDateTimeInterface $object
     *
     * @return mixed
     */
    public function encode($object)
    {
        $utc = date_create_from_format('U.u', $object->format('U.u'), timezone_open('UTC'));

        return array(
            'datetime' => $utc->format(self::DATETIME_FORMAT),
            'timezone' => $object->getTimezone()->getName(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $className)
    {
        switch ($className) {
            case 'DateTime':
                $datetime = DateTime::createFromFormat(
                    self::DATETIME_FORMAT,
                    $data['datetime'],
                    timezone_open('UTC')
                );

                $datetime->setTimezone(timezone_open($data['timezone']));

                return $datetime;
            case 'DateTimeImmutable':
                $datetime = DateTimeImmutable::createFromFormat(
                    self::DATETIME_FORMAT,
                    $data['datetime'],
                    timezone_open('UTC')
                );

                return $datetime->setTimezone(timezone_open($data['timezone']));
            default:
                throw new \RuntimeException('unsupported type: '.$data['class']);
        }
    }
}
