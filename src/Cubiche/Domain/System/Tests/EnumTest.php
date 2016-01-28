<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\System\Tests;

use Cubiche\Domain\Core\Tests\NativeValueObjectTestCase;

/**
 * Enum Test.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EnumTest extends NativeValueObjectTestCase
{
    /**
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct(TestEnum::class, $name, $data, $dataName);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\Tests\NativeValueObjectTestCase::firstNativeValue()
     */
    protected function firstNativeValue()
    {
        return TestEnum::FOO;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Core\Tests\NativeValueObjectTestCase::secondNativeValue()
     */
    protected function secondNativeValue()
    {
        return TestEnum::BAR;
    }

    /**
     * @test
     */
    public function is()
    {
        $value = $this->firstValue();
        $this->assertTrue($value->is($this->firstNativeValue()));
        $this->assertFalse($value->is($this->secondNativeValue()));
    }
}
