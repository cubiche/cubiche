<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Equatable\Tests\Asserters;

use Cubiche\Domain\Equatable\EquatableInterface;
use mageekguy\atoum\asserters\variable as BaseVariable;

/**
 * VariableAsserter class.
 *
 * @method $this isCallable
 * @method $this isIdenticalTo
 * @method $this isNotCallable
 * @method $this isNotEqualTo
 * @method $this isNotIdenticalTo
 * @method $this isNull
 * @method $this isNotNull
 * @method $this isNotTrue
 * @method $this isNotFalse
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class VariableAsserter extends BaseVariable
{
    /**
     * {@inheritdoc}
     */
    public function isEqualTo($value, $failMessage = null)
    {
        $target = $this->valueIsSet()->value;
        if ($target instanceof EquatableInterface) {
            if ($target->equals($value)) {
                $this->pass();
            } else {
                if (!$failMessage) {
                    $failMessage = $this->_(
                        '%s is not equal to %s',
                        $this,
                        $this->getTypeOf($value)
                    );
                }

                $this->fail($failMessage);
            }
        } else {
            parent::isEqualTo($value, $failMessage);
        }

        return $this;
    }
}
