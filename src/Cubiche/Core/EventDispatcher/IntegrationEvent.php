<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\EventDispatcher;

use Cubiche\Core\Bus\IntegrationMessage;
use Cubiche\Core\Validator\Assert;

/**
 * IntegrationEvent class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class IntegrationEvent extends IntegrationMessage implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function stopPropagation()
    {
        $this->payload['propagationStopped'] = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped(): bool
    {
        return $this->payload['propagationStopped'];
    }

    /**
     * @param array $payload
     */
    protected static function assert(array $payload)
    {
        parent::assert($payload);

        Assert::keyExists(
            $payload,
            'propagationStopped',
            sprintf('%s payload must contain a key propagationStopped', get_called_class())
        );

        Assert::boolean(
            $payload['propagationStopped'],
            sprintf('%s payload propagationStopped must be a valid UUID string', get_called_class())
        );
    }
}
