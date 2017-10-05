<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Cache;

use Cubiche\Core\Metadata\ClassMetadata;

/**
 * FileCache class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FileCache implements CacheInterface
{
    /**
     * @var string
     */
    protected $cacheDirectory;

    /**
     * FileCache constructor.
     *
     * @param string $cacheDirectory
     */
    public function __construct($cacheDirectory)
    {
        if (!is_dir($cacheDirectory)) {
            mkdir($cacheDirectory, 0755, true);
        }

        if (!is_writable($cacheDirectory)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" is not writable.', $cacheDirectory));
        }

        $this->cacheDirectory = rtrim($cacheDirectory, '\\/');
    }

    /**
     * {@inheritdoc}
     */
    public function load($className)
    {
        $filename = $this->fileName($className);
        if (!file_exists($filename)) {
            return;
        }

        return include $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ClassMetadata $metadata)
    {
        $filename = $this->fileName($metadata->className());

        $tmpFile = tempnam($this->cacheDirectory, 'metadata-cache');
        file_put_contents($tmpFile, '<?php return unserialize('.var_export(serialize($metadata), true).');');

        @chmod($tmpFile, 0666 & ~umask());
        $this->renameFile($tmpFile, $filename);
    }

    /**
     * Renames a file with fallback for windows.
     *
     * @param string $source
     * @param string $target
     */
    private function renameFile($source, $target)
    {
        if (false === @rename($source, $target)) {
            throw new \RuntimeException(sprintf('Could not write new cache file to %s.', $target));
        }
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function fileName($className)
    {
        return $this->cacheDirectory.'/'.strtr($className, '\\', '-').'.cache.php';
    }
}
