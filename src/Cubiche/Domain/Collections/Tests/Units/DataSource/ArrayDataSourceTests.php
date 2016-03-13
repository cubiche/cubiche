<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Units\DataSource;

use Cubiche\Domain\Collections\DataSource\ArrayDataSource;
use Cubiche\Domain\Collections\Tests\Units\Fixtures\EquatableObject;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Specification\SpecificationInterface;

/**
 * ArrayDataSourceTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayDataSourceTests extends IteratorDataSourceTests
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\DataSource\IteratorDataSourceTests::randomDataSource()
     */
    protected function randomDataSource(
        SpecificationInterface $searchCriteria = null,
        ComparatorInterface $sortCriteria = null,
        $offset = null,
        $length = null
    ) {
        $items = array();
        foreach (range(0, rand(10, 20)) as $value) {
            $items[] = new EquatableObject($value);
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
        return new EquatableObject(1000);
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
