<?php

/**
 * This file is part of the Cubiche/Bus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Async\Serializer;

use Cubiche\Core\Validator\Assert;

/**
 * Envelope class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Envelope implements EnvelopeInterface
{
    /**
     * @var string
     */
    private $messageName;

    /**
     * @var string
     */
    private $messageType;

    /**
     * @var array
     */
    private $serializedMessage;

    /**
     * Envelope constructor.
     *
     * @param string $messageName
     * @param string $messageType
     * @param array  $serializedMessage
     */
    public function __construct(string $messageName, string $messageType, array $serializedMessage)
    {
        $this->setMessageName($messageName);
        $this->setMessageType($messageType);
        $this->setSerializedMessage($serializedMessage);
    }

    /**
     * @return string
     */
    public function messageName(): string
    {
        return $this->messageName;
    }

    /**
     * @param string $messageName
     */
    private function setMessageName(string $messageName)
    {
        $this->messageName = $messageName;
    }

    /**
     * @return string
     */
    public function messageType(): string
    {
        return $this->messageType;
    }

    /**
     * @param string $messageType
     */
    private function setMessageType(string $messageType)
    {
        $this->messageType = $messageType;
    }

    /**
     * @return array
     */
    public function serializedMessage(): array
    {
        return $this->serializedMessage;
    }

    /**
     * @param array $serializedMessage
     */
    private function setSerializedMessage(array $serializedMessage)
    {
        $this->serializedMessage = $serializedMessage;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
          'messageName' => $this->messageName,
          'messageType' => $this->messageType,
          'serializedMessage' => $this->serializedMessage,
        ];
    }

    /**
     * @param array $data
     *
     * @return EnvelopeInterface
     */
    public static function fromArray(array $data): EnvelopeInterface
    {
        $className = get_called_class();

        Assert::keyExists($data, 'messageName', sprintf('%s must contain a key messageName', $className));
        Assert::keyExists($data, 'messageType', sprintf('%s must contain a key messageType', $className));
        Assert::keyExists($data, 'serializedMessage', sprintf('%s must contain a key serializedMessage', $className));

        return new static($data['messageName'], $data['messageType'], $data['serializedMessage']);
    }
}
