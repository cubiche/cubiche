<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Tests\Asserters;

use mageekguy\atoum\asserters\mock as BaseMock;

/**
 * MockAsserter class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MockAsserter extends BaseMock
{
    /**
     * {@inheritdoc}
     *
     * @see \mageekguy\atoum\asserter::__call()
     */
    public function __call($method, $arguments)
    {
        switch ($method) {
            case 'with':
                return $this->callIsSet();

            case 'arguments':
                if (sizeof($arguments) < 2 || !\is_callable($arguments[1])) {
                    throw new \Exception(
                        'arguments asserted require a int for the argument 0 and a callable function as 2 argument'
                    );
                }

                $currentCall = $this->getCall();

                $asserter = [];
                foreach ($this->getCalls($currentCall)->getEqualTo($currentCall)->toArray() as $call) {
                    $asserter = $call->getArguments();
                    break;
                }

                $argument = $asserter[$arguments[0]];
                $arguments[1]($argument);

                return $this->callIsSet();
            default:
                return parent::__call($method, $arguments);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \mageekguy\atoum\asserters\adapter::__get()
     */
    public function __get($property)
    {
        switch (strtolower($property)) {
            case 'with':
                return $this->{$property}();

            default:
                return parent::__get($property);
        }
    }

    /**
     * @return $this
     */
    public function with()
    {
        return $this->callIsSet();
    }
}
