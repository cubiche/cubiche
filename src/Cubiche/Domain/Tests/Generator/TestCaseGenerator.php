<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Generator;

/**
 * TestCaseGenerator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class TestCaseGenerator extends TestGenerator
{
    /**
     * TestCaseGenerator constructor.
     *
     * @param string $className
     * @param string $testDirectoryName
     * @param string $sourceFile
     * @param string $targetClassName
     * @param string $targetSourceFile
     */
    public function __construct(
        $className,
        $testDirectoryName,
        $sourceFile = '',
        $targetClassName = '',
        $targetSourceFile = ''
    ) {
        parent::__construct(
            $className,
            $testDirectoryName,
            $sourceFile,
            $targetClassName,
            $targetSourceFile
        );

        $this->className['className'] = $this->getTestsClassName($this->className['className']);
    }

    /**
     * @param $sourceFile
     * @param $className
     *
     * @return string
     */
    protected function calculateTargetSourceFile($sourceFile, $className)
    {
        $reflector = new \ReflectionClass($className);
        $namespace = $reflector->getNamespaceName();

        $projectName = '';
        $layerName = '';
        $componentName = '';

        $components = explode('\\', $namespace);
        if (count($components) > 0) {
            $projectName = $components[0];

            if (count($components) > 1) {
                $layerName = $components[1];

                if (count($components) > 2) {
                    $componentName = $components[2];
                }
            }
        }

        // for example: RealTests
        $components = explode('\\', $className);
        $targetClassName = $this->getTestsClassName(array_pop($components));

        // for example: Cubiche/Domain/Core
        $componentPath = $projectName.DIRECTORY_SEPARATOR.
            $layerName.DIRECTORY_SEPARATOR.$componentName
        ;

        // for example: src
        $begining = substr($sourceFile, 0, strpos($sourceFile, $componentPath) - 1);

        // for example: src/Cubiche/Domain/Core/Tests/Units/RealTests.php
        return $begining.DIRECTORY_SEPARATOR.
            $componentPath.DIRECTORY_SEPARATOR.
            $this->testDirectoryName.
            $targetClassName.'.php'
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTestsClassTemplate()
    {
        return 'TestCaseClass.tpl';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTestsClassName($className)
    {
        return 'TestCase';
    }
}
