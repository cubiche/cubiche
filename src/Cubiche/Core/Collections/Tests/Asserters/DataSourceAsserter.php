<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\Tests\Asserters;

use Cubiche\Core\Collections\DataSource\DataSourceInterface;
use mageekguy\atoum\asserters\object as ObjectAsserter;

/**
 * DataSourceAsserter class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DataSourceAsserter extends ObjectAsserter
{
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
