<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Storage\Tests\Units;

use Cubiche\Core\Serializer\DefaultSerializer;
use Cubiche\Core\Storage\Exception\InvalidKeyException;

/**
 * AbstractStorageTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AbstractStorageTests extends TestCase
{
    /**
     * Test validateKey method.
     */
    public function testValidateKey()
    {
        $this
            ->given($this->newTestedInstance(new DefaultSerializer()))
            ->then()
                ->boolean($this->invoke($this->testedInstance)->validateKey(''))->isTrue()
                ->exception(
                    function () {
                        $this->invoke($this->testedInstance)->validateKey(new \stdClass());
                    }
                )->isInstanceOf(InvalidKeyException::class)
        ;
    }
}
