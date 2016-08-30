<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Versioning;

use Cubiche\Core\Comparable\ComparableInterface;
use Cubiche\Core\Serializer\SerializableInterface;

/**
 * Version class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Version implements SerializableInterface, ComparableInterface
{
    /**
     * @var int
     */
    protected $major;

    /**
     * @var int
     */
    protected $minor;

    /**
     * @var int
     */
    protected $patch;

    /**
     * Version constructor.
     *
     * @param int $major
     * @param int $minor
     * @param int $patch
     */
    public function __construct($major = 0, $minor = 0, $patch = 0)
    {
        $this->setMajor($major);
        $this->setMinor($minor);
        $this->setPatch($patch);
    }

    /**
     * @return int
     */
    public function major()
    {
        return $this->major;
    }

    /**
     * @param int $major
     */
    public function setMajor($major)
    {
        $this->major = $major;
    }

    /**
     * @return int
     */
    public function minor()
    {
        return $this->minor;
    }

    /**
     * @param int $minor
     */
    public function setMinor($minor)
    {
        $this->minor = $minor;
    }

    /**
     * @return int
     */
    public function patch()
    {
        return $this->patch;
    }

    /**
     * @param int $patch
     */
    public function setPatch($patch)
    {
        $this->patch = $patch;
    }

    /**
     * Increment the patch version.
     *
     * @param VersionIncrementType $type
     */
    public function increment(VersionIncrementType $type)
    {
        switch ($type) {
            case VersionIncrementType::MAJOR():
                ++$this->major;
                $this->minor = 0;
                $this->patch = 0;
                break;
            case VersionIncrementType::MINOR():
                ++$this->minor;
                $this->patch = 0;
                break;
            default:
                ++$this->patch;
                break;
        }
    }

    /**
     * @return bool
     */
    public function isMinorVersion()
    {
        return $this->patch === 0 && $this->minor !== 0;
    }

    /**
     * @return bool
     */
    public function isMajorVersion()
    {
        return $this->patch === 0 && $this->minor === 0 && $this->major !== 0;
    }

    /**
     * @param string $value
     *
     * @return \Cubiche\Domain\EventSourcing\Versioning\Version
     */
    public static function fromString($value)
    {
        list($major, $minor, $patch) = array_pad(explode('.', $value), 3, 0);

        return new static(intval($major), intval($minor), intval($patch));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s.%s.%s', $this->major, $this->minor, $this->patch);
    }

    /**
     * @return int
     */
    public function toInt()
    {
        return intval(sprintf('%s%s%s', $this->major, $this->minor, $this->patch));
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($other)
    {
        if (!$other instanceof self) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "%s".',
                $other,
                self::class
            ));
        }

        return $this->toInt() == $other->toInt() ? 0 : ($this->toInt() > $other->toInt() ? 1 : -1);
    }
}
