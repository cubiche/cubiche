<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Asserters;

use Cubiche\Domain\Collections\DataSource\DataSourceInterface;
use mageekguy\atoum\asserters\object as Object;

/**
 * DataSourceAsserter class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DataSourceAsserter extends Object
{
    //    /**
//     * {@inheritdoc}
//     */
//    public function __get($asserter)
//    {
//        switch (strtolower($asserter)) {
//            case 'size':
//                return $this->size();
//            case 'isempty':
//                return $this->isEmpty();
//            case 'isnotempty':
//                return $this->isNotEmpty();
//            case 'hasallelements':
//                return $this->hasAllElements();
//            case 'hasnoelements':
//                return $this->hasNoElements();
//            case 'issorted':
//                return $this->isSorted();
//            case 'isnotsorted':
//                return $this->isNotSorted();
//            default:
//                return parent::__get($asserter);
//        }
//    }

    /**
     * {@inheritdoc}
     */
    public function setWith($value, $checkType = true)
    {
        parent::setWith($value, $checkType);

        if ($checkType === true) {
            if (self::isDataSource($this->value) === false) {
                $this->fail($this->getLocale()->_('%s is not an datasource', $this));
            } else {
                $this->pass();
            }
        }

        return $this;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    protected static function isDataSource($value)
    {
        return $value instanceof DataSourceInterface;
    }

    /**
     * {@inheritdoc}
     */
    protected function valueIsSet($message = 'DataSource is undefined')
    {
        return parent::valueIsSet($message);
    }
}
