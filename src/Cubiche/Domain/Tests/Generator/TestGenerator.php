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
     * @param string $sourceFile
     * @param string $targetClassName
     * @param string $targetSourceFile
     */
    public function __construct($className, $sourceFile = '', $targetClassName = '', $targetSourceFile = '')
    {
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
            $targetClassName = array_pop($components).'Tests';
        }

        if (empty($targetSourceFile)) {
            $targetSourceFile = dirname($sourceFile).DIRECTORY_SEPARATOR.
                'Tests'.DIRECTORY_SEPARATOR.
                'Units'.DIRECTORY_SEPARATOR.
                $targetClassName.'.php'
            ;
        }

        parent::__construct(
            $className,
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
                '%s%sTemplates%sTestClass.tpl',
                __DIR__,
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR
            )
        );

        if ($this->targetClassName['namespace'] != '') {
            $namespace = "\nnamespace ".$this->targetClassName['namespace'].";\n";
        } else {
            $namespace = '';
        }

        $methods = $methods.$incompleteMethods;
        if (empty($methods)) {
            $methodTemplate = new Template(
                sprintf(
                    '%s%sTemplates%sNoneMethod.tpl',
                    __DIR__,
                    DIRECTORY_SEPARATOR,
                    DIRECTORY_SEPARATOR
                )
            );

            $methodTemplate->setVar(
                array(
                    'className' => $this->className['className'],
                )
            );

            $methods = $methodTemplate->render();
            $use .= 'use '.$this->className['fullClassName'].";\n";
        }

        $classTemplate->setVar(
            array(
                'projectName' => $this->className['projectName'],
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
}
