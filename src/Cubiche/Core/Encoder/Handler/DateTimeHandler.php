<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Encoder\Handler;

use Cubiche\Core\Encoder\Context\ContextInterface;
use Cubiche\Core\Encoder\Visitor\DeserializationVisitor;
use Cubiche\Core\Encoder\Visitor\SerializationVisitor;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use RuntimeException;

/**
 * DateTimeHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DateTimeHandler implements HandlerSubscriberInterface
{
    /**
     * @var string
     */
    protected $defaultFormat;

    /**
     * DateTimeHandler constructor.
     *
     * @param string $defaultFormat
     */
    public function __construct($defaultFormat = "Y-m-d\TH:i:s.u\Z")
    {
        $this->defaultFormat = $defaultFormat;
    }

    /**
     * @param SerializationVisitor $visitor
     * @param DateTimeInterface    $date
     * @param array                $type
     * @param ContextInterface     $context
     *
     * @return mixed
     */
    public function serialize(
        SerializationVisitor $visitor,
        DateTimeInterface $date,
        array $type,
        ContextInterface $context
    ) {
        $format = $this->getFormat($type);
        if ('U' === $format) {
            return $visitor->visitInteger($date->format($format), $type, $context);
        }

        return array(
            'datetime' => $visitor->visitString($date->format($this->getFormat($type)), $type, $context),
            'timezone' => $visitor->visitString($date->getTimezone()->getName(), $type, $context),
        );
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
        return $this->parseDateTime($data, $type, $type['name'] == 'DateTimeImmutable');
    }

    /**
     * @param array $data
     * @param array $type
     * @param bool  $immutable
     *
     * @return $this|bool|DateTime|DateTimeImmutable
     */
    protected function parseDateTime(array $data, array $type, $immutable = false)
    {
        $format = $this->getDeserializationFormat($type);
        $timezone = new DateTimeZone($data['timezone']);

        if ($immutable) {
            $datetime = DateTimeImmutable::createFromFormat($format, $data['datetime'], $timezone);
        } else {
            $datetime = DateTime::createFromFormat($format, $data['datetime'], $timezone);
        }

        if (false === $datetime) {
            throw new RuntimeException(
                sprintf('Invalid datetime "%s", expected format %s.', $data['datetime'], $format)
            );
        }

        if ($format === 'U') {
            $datetime = $datetime->setTimezone($data['timezone']);
        }

        return $datetime;
    }

    /**
     * @param array $type
     *
     *  @return string
     */
    protected function getDeserializationFormat(array $type)
    {
        if (isset($type['params'][2])) {
            return $type['params'][2];
        }

        if (isset($type['params'][0])) {
            return $type['params'][0];
        }

        return $this->defaultFormat;
    }

    /**
     * @param array $type
     *
     * @return string
     */
    protected function getFormat(array $type)
    {
        return isset($type['params'][0]) ? $type['params'][0] : $this->defaultFormat;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedHandlers()
    {
        return array(
            'serializers' => array(
                DateTime::class => 'serialize',
                DateTimeImmutable::class => 'serialize',
            ),
            'deserializers' => array(
                DateTime::class => 'deserialize',
                DateTimeImmutable::class => 'deserialize',
            ),
        );
    }
}
