<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * AggregateRoot class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 *
 * @Annotation
 * @Target("CLASS")
 */
final class AggregateRoot extends Annotation
{
}
