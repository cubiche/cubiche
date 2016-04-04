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
 * AbstractGenerator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractGenerator
{
    /**
     * @var array
     */
    protected $className;

    /**
     * @var string
     */
    protected $testDirectoryName;

    /**
     * @var string
     */
    protected $sourceFile;

    /**
     * @var array
     */
    protected $targetClassName;

    /**
     * @var string
     */
    protected $targetSourceFile;

    /**
     * AbstractGenerator constructor.
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
        $this->className = $this->extractClassNameInfo($className);
        $this->targetClassName = $this->extractClassNameInfo($targetClassName);

        $this->sourceFile = str_replace(
            $this->className['fullClassName'],
            $this->className['className'],
            $sourceFile
        );

        $this->targetSourceFile = str_replace(
            $this->targetClassName['fullClassName'],
            $this->targetClassName['className'],
            $targetSourceFile
        );
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className['className'];
    }

    /**
     * @return string
     */
    public function getSourceFile()
    {
        return $this->sourceFile;
    }

    /**
     * @return string
     */
    public function getTargetClassName()
    {
        return $this->targetClassName['fullClassName'];
    }

    /**
     * @return string
     */
    public function getTargetSourceFile()
    {
        return $this->targetSourceFile;
    }

    /**
     * @param string $file
     * @param bool   $force
     *
     * @return int
     */
    public function write($file = '', $force = false)
    {
        if ($file == '') {
            $file = $this->targetSourceFile;
        }

        if (!is_file($file) || $force) {
            $directory = dirname($file);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            return file_put_contents($file, $this->generate());
        }

        throw new \RuntimeException('The file exists');
    }

    /**
     * @param $className
     *
     * @return array
     */
    protected function extractClassNameInfo($className)
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
            $result['namespace'] = $this->mergeClassName($tmp);
        } else {
            $refClass = new \ReflectionClass(
                $this->className['fullClassName']
            );

            $result['namespace'] = $refClass->getNamespaceName();
        }

        $result['namespace'] .= substr(
            '\\'.implode('\\', explode(DIRECTORY_SEPARATOR, $this->testDirectoryName)),
            0,
            -1
        );

        $components = explode('\\', $result['namespace']);
        if (count($components) > 0) {
            $result['projectName'] = $components[0];

            if (count($components) > 1) {
                $result['layerName'] = $components[1];

                if (count($components) > 2) {
                    $result['componentName'] = $components[2];
                }
            }
        }

        return $result;
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
     * @return string
     */
    abstract public function generate();
}
