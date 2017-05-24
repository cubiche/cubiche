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
 * Id class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 *
 * @Annotation
 * @Target("PROPERTY")
 */
final class Id extends Annotation
{
    public $name = '_id';
    public $type;
    public $id = true;
}
