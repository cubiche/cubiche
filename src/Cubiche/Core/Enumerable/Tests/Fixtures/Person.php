<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Enumerable\Tests\Fixtures;

use Cubiche\Core\Equatable\Equatable;

/**
 * Person class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Person extends Equatable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $age;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $weight;

    /**
     * @param string $name
     * @param int    $age
     * @param int    $height
     * @param int    $weight
     */
    public function __construct($name, $age, $height, $weight)
    {
        $this->name = $name;
        $this->age = $age;
        $this->height = $height;
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function age()
    {
        return $this->age;
    }

    /**
     * @return int
     */
    public function height()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function weight()
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return \json_encode(array(
            'name' => $this->name,
            'age' => $this->age,
            'height' => $this->height.' cm',
            'weight' => $this->weight.' kg',
        ));
    }
}
