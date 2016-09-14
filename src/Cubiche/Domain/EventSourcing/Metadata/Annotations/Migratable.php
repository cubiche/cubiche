<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Metadata\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Migratable class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 *
 * @Annotation
 * @Target("CLASS")
 */
final class Migratable extends Annotation
{
}
