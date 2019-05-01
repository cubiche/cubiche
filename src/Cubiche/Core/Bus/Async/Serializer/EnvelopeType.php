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

use Cubiche\Core\Enum\Enum;

/**
 * EnvelopeType Enum.
 *
 * @method static EnvelopeType COMMAND()
 * @method static EnvelopeType QUERY()
 * @method static EnvelopeType EVENT()
 * @method static EnvelopeType MESSAGE()
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
final class EnvelopeType extends Enum
{
    const COMMAND = 'command';
    const QUERY = 'query';
    const EVENT = 'event';
    const MESSAGE = 'message';
}
