<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector\Tests\Units;

/**
 * Key Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class KeyTests extends FieldTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitKey';
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            /* @var \Cubiche\Core\Selector\Key $key */
            ->given($key = $this->newTestedInstance('foo'))
            ->then()
                ->string($key->apply(array('foo' => 'bar')))
                    ->isEqualTo('bar')
                ->variable($key->apply(null))
                    ->isNull()
                ->variable($key->apply(array()))
                    ->isNull()
        ;
    }
}
