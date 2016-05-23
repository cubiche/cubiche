<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collection;

use Cubiche\Core\Collection\DataSource\DataSourceInterface;

/**
 * DataSourceCollection Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
trait DataSourceCollectionTrait
{
    /**
     * @var DataSourceInterface
     */
    protected $dataSource;

    /**
     * @param DataSourceInterface $dataSource
     */
    public function __construct(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->isInitialized()) {
            return parent::count();
        }

        return $this->dataSource->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if ($this->isInitialized()) {
            return parent::getIterator();
        }

        return $this->dataSource->getIterator();
    }
}
