<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Persistence\Tests\Units;

use Cubiche\Domain\Collections\Comparator\Sort;
use Cubiche\Domain\Collections\Tests\Units\CollectionTestCase;
use Cubiche\Domain\Persistence\RepositoryInterface;
use Cubiche\Domain\Persistence\Tests\Fixtures\User;
use Cubiche\Domain\Persistence\Tests\Fixtures\UserId;
use Cubiche\Domain\Specification\Criteria;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * Repository Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class RepositoryTestCase extends CollectionTestCase
{
    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param \Closure  $reflectionClassFactory
     * @param \Closure  $phpExtensionFactory
     * @param Analyzer  $analyzer
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        \Closure $reflectionClassFactory = null,
        \Closure $phpExtensionFactory = null,
        Analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );

        $this->getAssertionManager()
            ->setHandler(
                'randomRepository',
                function ($size = null) {
                    return $this->randomRepository($size);
                }
            )
            ->setHandler(
                'emptyRepository',
                function () {
                    return $this->emptyRepository();
                }
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::randomCollection()
     */
    protected function randomCollection($size = null)
    {
        return $this->randomRepository($size);
    }

    /**
     * @param int $size
     *
     * @return \Cubiche\Domain\Persistence\RepositoryInterface
     */
    protected function randomRepository($size = null)
    {
        $repository = $this->emptyRepository();
        $repository->addAll($this->randomValues($size));

        return $repository;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::emptyCollection()
     */
    protected function emptyCollection()
    {
        return $this->emptyRepository();
    }

    /**
     * @return RepositoryInterface
     */
    abstract protected function emptyRepository();

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::randomValue()
     */
    protected function randomValue()
    {
        return new User(UserId::next(), 'User-'.\rand(1, 100), \rand(1, 100));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Tests\Units\CollectionTestCase::uniqueValue()
     */
    protected function uniqueValue()
    {
        return new User(UserId::next(), 'Methuselah', 1000);
    }

    protected function comparator()
    {
        return Sort::by(Criteria::property('age'));
    }
}
