<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Tests\Fixtures;

use Cubiche\Domain\Geolocation\Coordinate;
use Cubiche\Domain\Model\Entity;

/**
 * City.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class City extends Entity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Coordinate
     */
    protected $coordinate;

    /**
     * City constructor.
     *
     * @param CityId     $id
     * @param string     $name
     * @param Coordinate $coordinate
     */
    public function __construct(CityId $id, $name, Coordinate $coordinate)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->coordinate = $coordinate;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Coordinate
     */
    public function coordinate()
    {
        return $this->coordinate;
    }
}
