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
 * ClassUtils class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ClassUtils
{
    /**
     * @param string $sourceFile
     * @param int    $tokenType
     *
     * @return array
     */
    protected static function extractFromFile($sourceFile, $tokenType = T_CLASS)
    {
        $result = array();

        $tokens = token_get_all(file_get_contents($sourceFile));
        $currentNamespace = false;

        $numTokens = count($tokens);
        for ($i = 0; $i < $numTokens; ++$i) {
            if (is_string($tokens[$i])) {
                continue;
            }

            switch ($tokens[$i][0]) {
                case T_NAMESPACE:
                    $currentNamespace = $tokens[$i + 2][1];

                    for ($j = $i + 3; $j < $numTokens; $j += 2) {
                        if ($tokens[$j][0] == T_NS_SEPARATOR) {
                            $currentNamespace .= '\\'.$tokens[$j + 1][1];
                        } else {
                            break;
                        }
                    }
                    break;

                case $tokenType:
                    if (isset($tokens[$i + 2][1])) {
                        $currentName = trim($tokens[$i + 2][1]);
                        if (!empty($currentName)) {
                            if ($currentNamespace === false) {
                                $result[] = $currentName;
                            } else {
                                $result[] = $currentNamespace.'\\'.$currentName;
                            }
                        }
                    }

                    break;
            }
        }

        return $result;
    }

    /**
     * @param string $sourceFile
     *
     * @return array
     */
    public static function getClassesInFile($sourceFile)
    {
        return self::extractFromFile($sourceFile, T_CLASS);
    }

    /**
     * @param string $sourceFile
     *
     * @return array
     */
    public static function getInterfacesInFile($sourceFile)
    {
        return self::extractFromFile($sourceFile, T_INTERFACE);
    }

    /**
     * @param $className
     *
     * @return array
     */
    public static function extractClassNameInfo($className)
    {
        $result = array(
            'projectName' => '',
            'layerName' => '',
            'componentName' => '',
            'namespace' => '',
            'className' => $className,
            'fullClassName' => $className,
        );

        if (strpos($className, '\\') !== false) {
            $tmp = explode('\\', $className);
            $result['className'] = $tmp[count($tmp) - 1];
            $result['namespace'] = self::mergeClassName($tmp);
        } else {
            $reflector = new \ReflectionClass($className);

            $result['namespace'] = $reflector->getNamespaceName();
        }

        list($result['projectName'], $result['layerName'], $result['componentName']) = self::extractProjectInfo(
            $result['namespace']
        );

        return $result;
    }

    /**
     * @param $className
     *
     * @return array
     */
    public static function extractTestCaseNamespace($className)
    {
        $result = array(
            'projectName' => '',
            'layerName' => '',
            'componentName' => '',
            'namespace' => '',
            'className' => $className,
            'fullClassName' => $className,
        );

        if (strpos($className, '\\') !== false) {
            $tmp = explode('\\', $className);
            $result['className'] = $tmp[count($tmp) - 1];
            $result['namespace'] = self::mergeClassName($tmp);
        } else {
            $reflector = new \ReflectionClass($className);

            $result['namespace'] = $reflector->getNamespaceName();
        }

        list($result['projectName'], $result['layerName'], $result['componentName']) = self::extractProjectInfo(
            $result['namespace']
        );

        return $result;
    }

    /**
     * @param $namespace
     *
     * @return array
     */
    protected static function extractProjectInfo($namespace)
    {
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

        return [$projectName, $layerName, $componentName];
    }

    /**
     * @param array $parts
     *
     * @return string
     */
    protected function mergeClassName(array $parts)
    {
        $result = '';
        if (count($parts) > 1) {
            array_pop($parts);
            $result = implode('\\', $parts);
        }

        return $result;
    }

    /**
     * @param $className
     *
     * @return string
     */
    public static function resolveSourceFile($className)
    {
        $sourceFile = '';
        if (class_exists($className)) {
            $reflector = new \ReflectionClass($className);
            $sourceFile = $reflector->getFileName();

            if ($sourceFile === false) {
                $sourceFile = '<internal>';
            }
        } else {
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
        }

        return realpath($sourceFile);
    }

    /**
     * @param string $className
     * @param string $sourceFile
     * @param string $testDirectoryName
     * @param bool   $isRoot
     *
     * @return string
     */
    public static function resolveTargetClassName($className, $sourceFile, $testDirectoryName, $isRoot = false)
    {
        $components = explode('\\', $className);
        $targetClassName = array_pop($components).'Tests';

        // Source file test case: src/Cubiche/Domain/Command/Middlewares/Handler/HandlertMiddleware.php
        $reflector = new \ReflectionClass($className);
        $namespace = $reflector->getNamespaceName();

        list($projectName, $layerName, $componentName) = self::extractProjectInfo(
            $namespace
        );

        // $componentPath = Cubiche/Domain/Command
        $componentPath = $projectName.DIRECTORY_SEPARATOR.
            $layerName.DIRECTORY_SEPARATOR.$componentName
        ;

        // $begining = src
        $begining = substr($sourceFile, 0, strpos($sourceFile, $componentPath) - 1);

        $path = $begining.DIRECTORY_SEPARATOR.$componentPath;
        $path = explode(DIRECTORY_SEPARATOR, substr($sourceFile, strlen($path) + 1));
        if (!empty($path)) {
            array_pop($path);
        }

        // $end = Middlewares/Handler/
        $end = implode(DIRECTORY_SEPARATOR, $path);

        if ($isRoot) {
            // Cubiche\\Domain\\Command\\Tests\\Units\\TestCase.php
            return implode('\\', explode('/', $componentPath.DIRECTORY_SEPARATOR)).
                implode('\\', explode('/', $testDirectoryName)).
                $targetClassName
            ;
        }

        // Cubiche\\Domain\\Command\\Tests\\Units\\Middlewares\\Handler\\HandlertMiddlewareTests.php
        return implode('\\', explode('/', $componentPath.DIRECTORY_SEPARATOR)).
            implode('\\', explode('/', $testDirectoryName)).
            implode('\\', explode('/', empty($end) ? '' : $end.DIRECTORY_SEPARATOR)).
            $targetClassName
        ;
    }

    /**
     * @param string $className
     * @param string $testCaseClassName
     * @param string $testDirectoryName
     *
     * @return string
     */
    public static function resolveTestCaseClassName($className, $testCaseClassName, $testDirectoryName)
    {
        // Source file test case: src/Cubiche/Domain/Command/Middlewares/Handler/HandlertMiddleware.php
        $reflector = new \ReflectionClass($className);
        $namespace = $reflector->getNamespaceName();

        list($projectName, $layerName, $componentName) = self::extractProjectInfo(
            $namespace
        );

        // $componentPath = Cubiche/Domain/Command
        $componentPath = $projectName.DIRECTORY_SEPARATOR.
            $layerName.DIRECTORY_SEPARATOR.$componentName
        ;

        // Cubiche\\Domain\\Command\\Tests\\Units\\TestCase.php
        return implode('\\', explode('/', $componentPath.DIRECTORY_SEPARATOR)).
            implode('\\', explode('/', $testDirectoryName)).
            $testCaseClassName
        ;
    }

    /**
     * @param string $className
     * @param string $sourceFile
     * @param string $targetClassName
     * @param string $testDirectoryName
     * @param bool   $isRoot
     *
     * @return string
     */
    public static function resolveTargetSourceFile(
        $className,
        $sourceFile,
        $targetClassName,
        $testDirectoryName,
        $isRoot = false
    ) {
        $components = explode('\\', $targetClassName);
        $targetClassName = array_pop($components);

        // Source file test case: src/Cubiche/Domain/Command/Middlewares/Handler/HandlertMiddleware.php
        $reflector = new \ReflectionClass($className);
        $namespace = $reflector->getNamespaceName();

        list($projectName, $layerName, $componentName) = self::extractProjectInfo(
            $namespace
        );

        // $componentPath = Cubiche/Domain/Command
        $componentPath = $projectName.DIRECTORY_SEPARATOR.
            $layerName.DIRECTORY_SEPARATOR.$componentName
        ;

        // $begining = src
        $begining = substr($sourceFile, 0, strpos($sourceFile, $componentPath) - 1);

        $path = $begining.DIRECTORY_SEPARATOR.$componentPath;
        $path = explode(DIRECTORY_SEPARATOR, substr($sourceFile, strlen($path) + 1));
        if (!empty($path)) {
            array_pop($path);
        }

        // $end = Middlewares/Handler/
        $end = implode(DIRECTORY_SEPARATOR, $path);

        if ($isRoot) {
            // src/Cubiche/Domain/Command/Tests/Units/TestCase.php
            return $begining.DIRECTORY_SEPARATOR.
                $componentPath.DIRECTORY_SEPARATOR.
                $testDirectoryName.
                $targetClassName.'.php'
            ;
        }

        // src/Cubiche/Domain/Command/Tests/Units/Middlewares/Handler/HandlertMiddlewareTests.php
        return $begining.DIRECTORY_SEPARATOR.
            $componentPath.DIRECTORY_SEPARATOR.
            $testDirectoryName.
            (empty($end) ? '' : $end.DIRECTORY_SEPARATOR).
            $targetClassName.'.php'
        ;
    }

    /**
     * @param string $directory
     * @param string $testDirectoryName
     * @param string $extension
     *
     * @return array
     */
    public static function getFilesInDirectory($directory, $testDirectoryName, $extension)
    {
        $objects = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $files = [];
        foreach ($objects as $fileName => $object) {
            if (is_file($fileName) && !self::isTestFile($fileName, $testDirectoryName)) {
                $info = pathinfo($fileName);
                if (!isset($info['extension'])) {
                    continue;
                }

                if ($info['extension'] !== $extension) {
                    continue;
                }

                $files[] = $fileName;
            }
        }

        return $files;
    }

    /**
     * @param string $fileName
     * @param string $testDirectoryName
     *
     * @return bool
     */
    protected static function isTestFile($fileName, $testDirectoryName)
    {
        $isTestFile = true;

        $names = explode('/', $testDirectoryName);
        foreach ($names as $name) {
            if (!empty(trim($name))) {
                $isTestFile = $isTestFile && strpos($fileName, $name) !== false;
            }
        }

        return $isTestFile;
    }
}
