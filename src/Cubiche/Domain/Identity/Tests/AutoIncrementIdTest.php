<?php

/**
 *  * This file is part of the Cubiche package.
 *  *
 *  * Copyright (c) Cubiche
 *  *
 *  * For the full copyright and license information, please view the LICENSE
 *  * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Identity\Tests;

use Cubiche\Domain\System\Integer;

/**
 * AutoIncrement Id Test.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AutoIncrementIdTest extends IdTestCase
{
    /**
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct(Integer::class, $name, $data, $dataName);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\Tests\NativeValueObjectTestCase::firstNativeValue()
     */
    protected function firstNativeValue()
    {
        return 4;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\Tests\NativeValueObjectTestCase::secondNativeValue()
     */
    protected function secondNativeValue()
    {
        return 3467;
    }
}
