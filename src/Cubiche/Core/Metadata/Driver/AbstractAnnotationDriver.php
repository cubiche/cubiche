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

use Cubiche\Core\Metadata\Exception\MappingException;
use Cubiche\Tests\Generator\ClassUtils;
use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\AdvancedDriverInterface;

/**
 * AbstractAnnotationDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractAnnotationDriver implements AdvancedDriverInterface
{
    /**
     * @var Reader
     */
    protected $reader;

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
     * AbstractAnnotationDriver constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader, array $paths = array())
    {
        $this->reader = $reader;
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
}
