<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Locator;

use Cubiche\Core\Metadata\Exception\MappingException;

/**
 * DefaultFileLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultFileLocator implements FileLocatorInterface
{
    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var array
     */
    protected $prefixes = [];

    /**
     * @var string
     */
    private $separator;

    /**
     * Constructor.
     *
     * @param array  $prefixes
     * @param string $separator
     */
    public function __construct(array $prefixes, $separator = '.')
    {
        $this->addNamespacePrefixes($prefixes);
        if (empty($separator)) {
            throw new \InvalidArgumentException('The separator should not be empty');
        }

        $this->separator = (string) $separator;
    }

    /**
     * Adds Namespace Prefixes.
     *
     * @param array $prefixes
     */
    public function addNamespacePrefixes(array $prefixes)
    {
        $this->prefixes = array_merge($this->prefixes, $prefixes);
        $this->paths = array_merge($this->paths, array_keys($prefixes));
    }

    /**
     * @return array
     */
    public function prefixes()
    {
        return $this->prefixes;
    }

    /**
     * {@inheritdoc}
     */
    public function paths()
    {
        return $this->paths;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames($extension)
    {
        $classes = [];

        if ($this->paths) {
            foreach ((array) $this->paths as $path) {
                if (!is_dir($path)) {
                    throw MappingException::invalidDirectory($path);
                }

                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($iterator as $file) {
                    $fileName = $file->getBasename($extension);
                    if ($fileName == $file->getBasename()) {
                        continue;
                    }

                    $nsSuffix = strtr(
                        substr(realpath($file->getPath()), strlen(realpath($path))),
                        $this->separator,
                        '\\'
                    );

                    $classes[] = $this->prefixes[$path].
                        str_replace(DIRECTORY_SEPARATOR, '\\', $nsSuffix).'\\'.
                        str_replace($this->separator, '\\', $fileName)
                    ;
                }
            }
        }

        return $classes;
    }

    /**
     * {@inheritdoc}
     */
    public function findMappingFile($className, $extension)
    {
        foreach ($this->paths as $path) {
            $prefix = $this->prefixes[$path];

            if (0 !== strpos($className, $prefix.'\\')) {
                continue;
            }

            $filename = $path.'/'.strtr(substr($className, strlen($prefix) + 1), '\\', $this->separator).$extension;
            if (is_file($filename)) {
                return $filename;
            }
        }

        return;
    }
}
