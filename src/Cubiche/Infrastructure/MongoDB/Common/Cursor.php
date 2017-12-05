<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Common;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Serializer\SerializerInterface;
use IteratorIterator;
use MongoDB\Driver\Cursor as MongoDBCursor;

/**
 * Cursor class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Cursor implements Iterator
{
    /**
     * The MongoDB Cursor instance being wrapped.
     *
     * @var IteratorIterator
     */
    protected $iterator;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var ClassMetadata
     */
    protected $classMetadata;

    /**
     * Cursor constructor.
     *
     * @param MongoDBCursor       $cursor
     * @param SerializerInterface $serializer
     * @param ClassMetadata       $classMetadata
     */
    public function __construct(MongoDBCursor $cursor, SerializerInterface $serializer, ClassMetadata $classMetadata)
    {
        $this->iterator = new IteratorIterator($cursor);
        $this->serializer = $serializer;
        $this->classMetadata = $classMetadata;
    }

    /**
     * Recreates the command iterator and counts its results.
     *
     * @return int
     */
    public function count()
    {
        return count($this->getBaseCursor()->toArray());
    }

    /**
     * @return object|array|null
     */
    public function current()
    {
        return $this->hydrateDocument($this->iterator->current());
    }

    /**
     * @return bool
     */
    public function dead()
    {
        return $this->getBaseCursor()->isDead();
    }

    /**
     * @return MongoDBCursor
     */
    public function getBaseCursor()
    {
        return $this->iterator->getInnerIterator();
    }

    /**
     * @return object|array|null
     */
    public function getSingleResult()
    {
        $this->rewind();

        return $this->current();
    }

    /**
     * @param array $document
     *
     * @return array|object|null
     */
    private function hydrateDocument($document)
    {
        if ($document !== null) {
            $data = json_decode(
                json_encode($document),
                true
            );

            return $this->serializer->deserialize($data, $this->classMetadata->className());
        }

        return $document;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * @return array
     */
    public function rewind()
    {
        return $this->iterator->rewind();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->getBaseCursor()->toArray();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->iterator->valid();
    }
}
