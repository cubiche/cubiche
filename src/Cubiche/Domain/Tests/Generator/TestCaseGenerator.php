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
