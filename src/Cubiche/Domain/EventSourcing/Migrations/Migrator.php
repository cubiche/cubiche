<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations;

/**
 * Migrator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Migrator
{
    /**
     * @var int
     */
    protected $numberOfBelowVersions = 1;

    /**
     * @var int
     */
    protected $numberOfAboveVersions = 1;
}
