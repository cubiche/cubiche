<?php

/**
 * This file is part of the Cubiche/Bus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus;

use Cubiche\Core\Validator\Assert;

/**
 * IntegrationMessage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class IntegrationMessage implements MessageInterface
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * IntegrationMessage constructor.
     *
     * @param array $payload
     */
    private function __construct(array $payload)
    {
        $this->setPayload($payload);
    }

    /**
     * @return array
     */
    public function payload()
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     */
    private function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function messageId(): MessageId
    {
        return MessageId::fromNative($this->payload['messageId']);
    }

    /**
     * {@inheritdoc}
     */
    public function messageName(): string
    {
        return $this->payload['messageName'];
    }

    /**
     * @param array $payload
     *
     * @return static
     */
    static public function fromArray(array $payload)
    {
        self::assert($payload);

        return new static($payload);
    }

    /**
     * @param array $payload
     */
    protected static function assert(array $payload)
    {
        $className = get_called_class();

        Assert::keyExists(
            $payload,
            'messageId',
            sprintf('%s payload must contain a key messageId', $className)
        );

        Assert::keyExists(
            $payload,
            'messageName',
            sprintf('%s payload must contain a key messageName', $className)
        );

        Assert::uuid(
            $payload['messageId'],
            sprintf('%s payload messageId must be a valid UUID string', $className)
        );

        Assert::string(
            $payload['messageName'],
            sprintf('%s payload messageName must be a valid string', $className)
        );

        self::assertPayload($payload);
    }

    /**
     * @param mixed $payload
     */
    protected static function assertPayload($payload)
    {
        if (\is_array($payload)) {
            foreach ($payload as $subPayload) {
                self::assertPayload($subPayload);
            }
            return;
        }

        Assert::nullOrscalar(
            $payload,
            sprintf('%s payload must only contain arrays and scalar values', get_called_class())
        );
    }
}
