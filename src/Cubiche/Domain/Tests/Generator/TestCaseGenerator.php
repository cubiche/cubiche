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
     * {@inheritdoc}
     */
    public function resolveTargetClassName($className, $sourceFile, $testDirectoryName)
    {
        $targetClassName = ClassUtils::resolveTargetClassName(
            $className,
            $sourceFile,
            $testDirectoryName,
            $this->isTestCaseClass()
        );

        // replace the targetClassName by testCaseClassName
        $components = explode('\\', $targetClassName);
        array_pop($components);
        $components[] = $this->testCaseClassName;

        return implode('\\', $components);
    }

    /**
     * {@inheritdoc}
     */
    protected function isTestCaseClass()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTestsClassTemplate()
    {
        return 'TestCaseClass.tpl';
    }
}
