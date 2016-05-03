<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Fixtures;

use Cubiche\Domain\Model\AggregateRoot;
use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\StringLiteral;

/**
 * Category class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Category extends Entity
{
    /**
     * @var StringLiteral
     */
    protected $name;

    /**
     * @return StringLiteral
     */
    public function name()
    {
        return $this->name;
    }
}
