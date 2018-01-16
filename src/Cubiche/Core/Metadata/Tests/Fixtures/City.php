<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures;

use Cubiche\Domain\Model\Entity;
use Cubiche\Domain\System\StringLiteral;

/**
 * City class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class City extends Entity
{
    /**
     * @var IdInterface
     */
    protected $id;

    /**
     * @var StringLiteral
     */
    protected $name;

    /**
     * City constructor.
     *
     * @param CityId $id
     * @param string $name
     */
    public function __construct(CityId $id, $name)
    {
        parent::__construct($id);

        $this->name = StringLiteral::fromNative($name);
    }

    /**
     * @return StringLiteral
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param City $other
     *
     * @return bool
     */
    public function equals($other)
    {
        return $this->id()->equals($other->id()) &&
            $this->name() == $other->name()
        ;
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    abstract public function loadMetadata(ClassMetadata $classMetadata);
}
