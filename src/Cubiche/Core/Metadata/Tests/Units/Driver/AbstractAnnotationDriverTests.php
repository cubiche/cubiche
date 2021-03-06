<?php

/**
 * This file is part of the Cubiche/Metadata component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Units\Driver;

use Cubiche\Core\Metadata\Exception\MappingException;
use Cubiche\Core\Metadata\Tests\Fixtures\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * AbstractAnnotationDriverTests class.
 *
 * Generated by TestGenerator on 2017-05-16 at 13:17:21.
 */
class AbstractAnnotationDriverTests extends DriverTestCase
{
    /**
     * @var string
     */
    protected $cacheDirectory;

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

        $this->cacheDirectory = __DIR__.'/../Driver/Cache';
    }

    /**
     * Create the cache directory.
     */
    public function setUp()
    {
        if (!is_dir($this->cacheDirectory)) {
            mkdir($this->cacheDirectory);
        }
    }

    /**
     * Remove the cache directory.
     */
    public function tearDown()
    {
        $this->rmdir($this->cacheDirectory);
    }

    /**
     * @return AnnotationDriver
     */
    protected function createDriver()
    {
        $reader = new CachedReader(
            new AnnotationReader(),
            new FilesystemCache($this->cacheDirectory),
            $debug = true
        );

        AnnotationRegistry::registerFile(__DIR__.'/../../Fixtures/Annotations/AggregateRoot.php');
        AnnotationRegistry::registerFile(__DIR__.'/../../Fixtures/Annotations/Entity.php');
        AnnotationRegistry::registerFile(__DIR__.'/../../Fixtures/Annotations/Field.php');
        AnnotationRegistry::registerFile(__DIR__.'/../../Fixtures/Annotations/Id.php');

        $driver = new AnnotationDriver($reader, [__DIR__.'/../../Fixtures']);
        $driver->addExcludePaths([
            __DIR__.'/../../Fixtures/Annotations',
            __DIR__.'/../../Fixtures/Driver',
            __DIR__.'/../../Fixtures/mapping',
            __DIR__.'/../../Fixtures/mapping-two',
        ]);
        $driver->setFileExtension('.php');

        return $driver;
    }

    /**
     * @return AnnotationDriver
     */
    protected function createEmptyDriver()
    {
        $reader = new CachedReader(
            new AnnotationReader(),
            new FilesystemCache($this->cacheDirectory),
            $debug = true
        );

        return new AnnotationDriver($reader);
    }

    /**
     * @return AnnotationDriver
     */
    protected function createDriverWithInvalidPaths()
    {
        $reader = new CachedReader(
            new AnnotationReader(),
            new FilesystemCache($this->cacheDirectory),
            $debug = true
        );

        return new AnnotationDriver($reader, [__DIR__.'/../../Fixtures/Foo']);
    }

    /**
     * Test excludePaths method.
     */
    public function testExcludePaths()
    {
        $this
            ->given($driver = $this->createDriver())
            ->then()
                ->array($driver->excludePaths())
                    ->hasSize(4)
        ;
    }

    /**
     * Test getAllClassNames method.
     */
    public function testGetAllClassNames()
    {
        parent::testGetAllClassNames();

        $this
            ->given($driver = $this->createDriver())
            ->when($classNames = $driver->getAllClassNames())
            ->and($classNames = $driver->getAllClassNames())
            ->then()
                ->array($classNames)
                    ->hasSize(5)
        ;

        $this
            ->given($driver = $this->createEmptyDriver())
            ->then()
                ->exception(function () use ($driver) {
                    $driver->getAllClassNames();
                })->isInstanceOf(MappingException::class);

        $this
            ->given($driver = $this->createDriverWithInvalidPaths())
            ->then()
                ->exception(function () use ($driver) {
                    $driver->getAllClassNames();
                })->isInstanceOf(MappingException::class);
    }
}
