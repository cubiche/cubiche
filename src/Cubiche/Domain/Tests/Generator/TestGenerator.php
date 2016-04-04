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
        $this->testDirectoryName = $testDirectoryName;

        if (class_exists($className)) {
            $reflector = new \ReflectionClass($className);
            $sourceFile = $reflector->getFileName();

            if ($sourceFile === false) {
                $sourceFile = '<internal>';
            }

            unset($reflector);
        } else {
            if (empty($sourceFile)) {
                $possibleFilenames = array(
                    $className.'.php',
                    str_replace(
                        array('_', '\\'),
                        DIRECTORY_SEPARATOR,
                        $className
                    ).'.php',
                );

                foreach ($possibleFilenames as $possibleFilename) {
                    if (is_file($possibleFilename)) {
                        $sourceFile = $possibleFilename;
                    }
                }
            }

            if (empty($sourceFile)) {
                throw new \RuntimeException(
                    sprintf(
                        'Neither "%s" nor "%s" could be opened.',
                        $possibleFilenames[0],
                        $possibleFilenames[1]
                    )
                );
            }

            if (!is_file($sourceFile)) {
                throw new \RuntimeException(
                    sprintf(
                        '"%s" could not be opened.',
                        $sourceFile
                    )
                );
            }

            $sourceFile = realpath($sourceFile);
            include_once $sourceFile;

            if (!class_exists($className)) {
                throw new \RuntimeException(
                    sprintf(
                        'Could not find class "%s" in "%s".',
                        $className,
                        $sourceFile
                    )
                );
            }
        }

        if (empty($targetClassName)) {
            $components = explode('\\', $className);
            $targetClassName = $this->getTestsClassName(array_pop($components));
        }

        if (empty($targetSourceFile)) {
            $targetSourceFile = $this->calculateTargetSourceFile($sourceFile, $className);
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
        $class = new \ReflectionClass(
            $this->className['fullClassName']
        );

        $use = '';
        $methods = '';
        $incompleteMethods = '';

        foreach ($class->getMethods() as $method) {
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

        if ($this->targetClassName['className'] !== 'TestCase') {
            $testCaseUse = $this->getTestCaseNamespace();
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
     * @param $className
     *
     * @return string
     */
    protected function getTestsClassName($className)
    {
        return $className.'Tests';
    }

    /**
     * @return string
     */
    protected function getTestCaseNamespace()
    {
        $namespace = $this->className['projectName'].'\\'.
            $this->className['layerName'].'\\'.
            $this->className['componentName'].'\\'.
            implode('\\', explode(DIRECTORY_SEPARATOR, $this->testDirectoryName))
        ;

        return substr($namespace, 0, -1);
    }
}
