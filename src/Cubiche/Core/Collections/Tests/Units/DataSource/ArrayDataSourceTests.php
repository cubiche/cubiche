<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections\Tests\Units\DataSource;

use Cubiche\Core\Collections\DataSource\ArrayDataSource;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Equatable\Tests\Fixtures\Value;
use Cubiche\Core\Specification\SpecificationInterface;

/**
 * ArrayDataSourceTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayDataSourceTests extends IteratorDataSourceTests
{
    /**
     * {@inheritdoc}
     */
    protected function randomDataSource(
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        $items = array();
        foreach (range(0, rand(10, 20)) as $value) {
            $items[] = new Value($value);
        }

        return new ArrayDataSource($items, $searchCriteria, $sortCriteria, $offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyDataSource()
    {
        return new ArrayDataSource(array());
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return new Value(1000);
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($datasource = $this->randomDataSource())
            ->then
                ->datasource($datasource)
                    ->isInstanceOf(ArrayDataSource::class)
        ;
    }
}
