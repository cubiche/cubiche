<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Driver;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\ClassMetadataInterface;
use Cubiche\Core\Metadata\Exception\MappingException;
use Cubiche\Tests\Generator\ClassUtils;

/**
 * StaticDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class StaticDriver implements DriverInterface
{
    /**
     * The paths where to look for mapping files.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * The paths excluded from path where to look for mapping files.
     *
     * @var array
     */
    protected $excludePaths = [];

    /**
     * The file extension of mapping documents.
     *
     * @var string
     */
    protected $fileExtension = '.php';

    /**
     * @var array|null
     */
    protected $classNames;

    /**
     * The name of the method to call.
     *
     * @var string
     */
    protected $methodName;

    /**
     * StaticDriver constructor.
     *
     * @param string $methodName
     * @param array  $paths
     */
    public function __construct($methodName = 'loadMetadata', array $paths = array())
    {
        $this->methodName = $methodName;
        $this->addPaths($paths);
    }

    /**
     * @param array $paths
     */
    public function addPaths(array $paths)
    {
        $this->paths = array_unique(array_merge($this->paths, $paths));
    }

    /**
     * @param array $paths
     */
    public function addExcludePaths(array $paths)
    {
        $this->excludePaths = array_unique(array_merge($this->excludePaths, $paths));
    }

    /**
     * @return array
     */
    public function excludePaths()
    {
        return $this->excludePaths;
    }

    /**
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        if ($this->classNames !== null) {
            return $this->classNames;
        }

        if (!$this->paths) {
            throw MappingException::pathRequired();
        }

        $includedFiles = [];
        foreach ($this->paths as $path) {
            if (!is_dir($path)) {
                throw MappingException::invalidDirectory($path);
            }

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+'.preg_quote($this->fileExtension).'$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = $file[0];

                if (!preg_match('(^phar:)i', $sourceFile)) {
                    $sourceFile = realpath($sourceFile);
                }

                foreach ($this->excludePaths as $excludePath) {
                    $exclude = str_replace('\\', '/', realpath($excludePath));
                    $current = str_replace('\\', '/', $sourceFile);

                    if (strpos($current, $exclude) !== false) {
                        continue 2;
                    }
                }

                require_once $sourceFile;
                $includedFiles[] = $sourceFile;
            }
        }

        $this->classNames = [];
        foreach ($includedFiles as $includedFile) {
            $this->classNames = array_merge($this->classNames, ClassUtils::getClassesInFile($includedFile));
        }

        return $this->classNames;
    }

    /**
     * Loads the metadata for the specified class into the provided container.
     *
     * @param string $className
     *
     * @return ClassMetadataInterface|null
     */
    public function loadMetadataForClass($className)
    {
        try {
            $reflClass = new \ReflectionClass($className);
            if (!$reflClass->isInterface() && $reflClass->hasMethod($this->methodName)) {
                $reflMethod = $reflClass->getMethod($this->methodName);

                if ($reflMethod->isAbstract()) {
                    throw MappingException::invalidAbstractMethod(
                        $reflClass->name,
                        $this->methodName
                    );
                }

                if (!$reflMethod->isStatic()) {
                    throw MappingException::invalidStaticMethod(
                        $reflClass->name,
                        $this->methodName
                    );
                }

                $metadata = $this->createClassMetadata($reflClass->getName());

                $reflMethod->invoke(null, $metadata);

                return $metadata;
            }

            return;
        } catch (\ReflectionException $e) {
            throw MappingException::classNotFound($className);
        }
    }

    /**
     * @param string $className
     *
     * @return ClassMetadataInterface
     */
    protected function createClassMetadata($className)
    {
        return new ClassMetadata($className);
    }
}
