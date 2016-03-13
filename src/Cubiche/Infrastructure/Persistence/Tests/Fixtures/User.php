<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Tests\Fixtures;

use Cubiche\Domain\Model\AggregateRoot;

/**
 * User Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class User extends AggregateRoot
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
     * @param UserId $id
     * @param string $name
     * @param int    $age
     */
    public function __construct(UserId $id, $name, $age)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->age = $age;
    }

    /**
     * @return int
     */
    public function age()
    {
        return $this->age;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
