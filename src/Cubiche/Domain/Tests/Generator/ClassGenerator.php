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
 * ClassGenerator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassGenerator extends AbstractGenerator
{
    /**
     * ClassGenerator constructor.
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
        if (empty($sourceFile)) {
            $sourceFile = $className.'.php';
        }

        if (!is_file($sourceFile)) {
            throw new \RuntimeException(
                sprintf(
                    '"%s" could not be opened.',
                    $sourceFile
                )
            );
        }

        if (empty($targetClassName)) {
            $targetClassName = substr($className, 0, strlen($className) - 4);
        }

        if (empty($targetSourceFile)) {
            $targetSourceFile = dirname($sourceFile).DIRECTORY_SEPARATOR.$targetClassName.'.php';
        }

        parent::__construct(
            $className,
            $testDirectoryName,
            $sourceFile,
            $targetClassName,
            $targetSourceFile
        );
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $testedMethods = ClassUtils::getTestedMethods(
            $this->className['fullClassName'],
            $this->sourceFile,
            $this->targetClassName['fullClassName']
        );

        $methods = '';
        foreach ($testedMethods as $method) {
            $methodTemplate = new Template(
                sprintf(
                    '%s%sTemplates%sMethod.tpl',
                    __DIR__,
                    DIRECTORY_SEPARATOR,
                    DIRECTORY_SEPARATOR
                )
            );

            $methodTemplate->setVar(
                array('methodName' => $method)
            );

            $methods .= $methodTemplate->render();
        }

        $classTemplate = new Template(
            sprintf(
                '%s%sTemplates%sClass.tpl',
                __DIR__,
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR
            )
        );

        $classTemplate->setVar(
            array(
                'className' => $this->targetClassName['fullClassName'],
                'methods' => $methods,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
            )
        );

        return $classTemplate->render();
    }
}
