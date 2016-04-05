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
 * TestGenerator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class TestGenerator extends AbstractGenerator
{
    /**
     * @var string
     */
    protected $testCaseClassName;

    /**
     * ClassGenerator constructor.
     *
     * @param string $className
     * @param string $testCaseClassName
     * @param string $testDirectoryName
     * @param string $sourceFile
     * @param string $targetClassName
     * @param string $targetSourceFile
     */
    public function __construct(
        $className,
        $testCaseClassName,
        $testDirectoryName,
        $sourceFile = '',
        $targetClassName = '',
        $targetSourceFile = ''
    ) {
        $this->testCaseClassName = $testCaseClassName;

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
    protected function isTestCaseClass()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $reflector = new \ReflectionClass($this->className['fullClassName']);

        $use = '';
        $methods = '';
        $incompleteMethods = '';

        foreach ($reflector->getMethods() as $method) {
            if (!$method->isConstructor() &&
                !$method->isAbstract() &&
                $method->isPublic() &&
                $method->getDeclaringClass()->getName() == $this->className['fullClassName']) {
                $methodTemplate = new Template(
                    sprintf(
                        '%s%sTemplates%sIncompleteTestMethod.tpl',
                        __DIR__,
                        DIRECTORY_SEPARATOR,
                        DIRECTORY_SEPARATOR
                    )
                );

                $methodTemplate->setVar(
                    array(
                        'className' => $this->className['fullClassName'],
                        'methodName' => ucfirst($method->getName()),
                        'origMethodName' => $method->getName(),
                    )
                );

                $incompleteMethods .= $methodTemplate->render();
            }
        }

        $classTemplate = new Template(
            sprintf(
                '%s%sTemplates%s%s',
                __DIR__,
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR,
                $this->getTestsClassTemplate()
            )
        );

        if ($this->targetClassName['namespace'] != '') {
            $namespace = "\nnamespace ".$this->targetClassName['namespace'].";\n";
        } else {
            $namespace = '';
        }

        if (!$this->isTestCaseClass()) {
            $testCaseUse = $this->getTestCaseNamespace($this->className['fullClassName']);

            if ($testCaseUse !== $this->targetClassName['namespace']) {
                $use = 'use '.$testCaseUse."\\TestCase;\n";
            }
        }

        $methods = $methods.$incompleteMethods;
        if (empty($methods) && $this->targetClassName['className'] !== 'TestCase') {
            $methods = $this->renderNoneMethods($use);
        }

        $classTemplate->setVar(
            array(
                'projectName' => $this->className['projectName'],
                'layerName' => $this->className['layerName'],
                'componentName' => $this->className['componentName'],
                'namespace' => $namespace,
                'use' => $use,
                'className' => $this->className['className'],
                'testClassName' => $this->targetClassName['className'],
                'methods' => $methods,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
            )
        );

        return $classTemplate->render();
    }

    /**
     * @param $use
     *
     * @return string
     */
    protected function renderNoneMethods(&$use)
    {
        $reflector = new \ReflectionClass($this->className['fullClassName']);

        $methodTemplate = new Template(
            sprintf(
                '%s%sTemplates%sNoneMethod.tpl',
                __DIR__,
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR
            )
        );

        if ($reflector->isAbstract()) {
            $methodTemplate->setVar(
                array(
                    'asserter' => 'isAbstract()',
                    'className' => $this->className['className'],
                )
            );
        } else {
            $interfaces = $reflector->getInterfaceNames();
            if (empty($interfaces)) {
                if ($reflector->getParentClass()) {
                    $use .= 'use '.$reflector->getParentClass()->getName().";\n";
                    $methodTemplate->setVar(
                        array(
                            'asserter' => 'extends('.$reflector->getParentClass()->getName().'::class)',
                            'className' => $this->className['className'],
                        )
                    );
                }
            } else {
                $use .= 'use '.$interfaces[0].";\n";
                $components = explode('\\', $interfaces[0]);
                $methodTemplate->setVar(
                    array(
                        'asserter' => 'implements('.array_pop($components).'::class)',
                        'className' => $this->className['className'],
                    )
                );
            }
        }

        return $methodTemplate->render();
    }

    /**
     * @return string
     */
    protected function getTestsClassTemplate()
    {
        return 'TestClass.tpl';
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getTestCaseNamespace($className)
    {
        $testCaseFullClassName = ClassUtils::resolveTestCaseClassName(
            $className,
            $this->testCaseClassName,
            $this->testDirectoryName
        );

        $components = explode('\\', $testCaseFullClassName);
        array_pop($components);

        return implode('\\', $components);
    }
}
