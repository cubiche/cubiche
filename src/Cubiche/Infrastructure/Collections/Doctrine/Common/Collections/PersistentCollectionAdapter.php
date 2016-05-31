<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Collections\Doctrine\Common\Collections;

use Cubiche\Core\Collection\CollectionInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\PersistentCollection;

/**
 * Persistent Collection Adapter Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class PersistentCollectionAdapter extends PersistentCollection implements CollectionInterface
{
    /**
     * @var PersistentCollection
     */
    protected $collection;

    /**
     * @param PersistentCollection $collection
     */
    public function __construct(PersistentCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function setDocumentManager(DocumentManager $dm)
    {
        $this->collection->setDocumentManager($dm);
    }

    /**
     * {@inheritdoc}
     */
    public function setMongoData(array $mongoData)
    {
        $this->collection->setMongoData($mongoData);
    }

    /**
     * {@inheritdoc}
     */
    public function getMongoData()
    {
        return $this->collection->getMongoData();
    }

    /**
     * {@inheritdoc}
     */
    public function setHints(array $hints)
    {
        $this->collection->setHints($hints);
    }

    /**
     * {@inheritdoc}
     */
    public function getHints()
    {
        return $this->collection->getHints();
    }

    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        $this->collection->initialize();
    }

    /**
     * {@inheritdoc}
     */
    public function isDirty()
    {
        return $this->collection->isDirty();
    }

    /**
     * {@inheritdoc}
     */
    public function setDirty($dirty)
    {
        $this->collection->setDirty($dirty);
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner($document, array $mapping)
    {
        $this->collection->setOwner($document, $mapping);
    }

    /**
     * {@inheritdoc}
     */
    public function takeSnapshot()
    {
        $this->collection->takeSnapshot();
    }

    /**
     * {@inheritdoc}
     */
    public function clearSnapshot()
    {
        $this->collection->clearSnapshot();
    }

    /**
     * {@inheritdoc}
     */
    public function getSnapshot()
    {
        return $this->collection->getSnapshot();
    }

    /**
     * {@inheritdoc}
     */
    public function getDeleteDiff()
    {
        return $this->collection->getDeleteDiff();
    }

    /**
     * {@inheritdoc}
     */
    public function getDeletedDocuments()
    {
        return $this->collection->getDeletedDocuments();
    }

    /**
     * {@inheritdoc}
     */
    public function getInsertDiff()
    {
        return $this->collection->getInsertDiff();
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        return $this->collection->getOwner();
    }

    /**
     * {@inheritdoc}
     */
    public function getMapping()
    {
        return $this->collection->getMapping();
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeClass()
    {
        return $this->collection->getTypeClass();
    }

    /**
     * {@inheritdoc}
     */
    public function setInitialized($bool)
    {
        $this->collection->setInitialized($bool);
    }

    /**
     * {@inheritdoc}
     */
    public function isInitialized()
    {
        return $this->collection->isInitialized();
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        return $this->collection->last();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return $this->collection->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function removeElement($element)
    {
        return $this->collection->removeElement($element);
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key)
    {
        return $this->collection->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element)
    {
        return $this->collection->contains($element);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(\Closure $p)
    {
        return $this->collection->exists($p);
    }

    /**
     * {@inheritdoc}
     */
    public function indexOf($element)
    {
        return $this->collection->indexOf($element);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys()
    {
        return $this->collection->getKeys();
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return $this->collection->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function add($value)
    {
        return $this->collection->add($value);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->collection->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function map(\Closure $func)
    {
        return $this->collection->map($func);
    }

    /**
     * {@inheritdoc}
     */
    public function filter(\Closure $p)
    {
        return $this->collection->filter($p);
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(\Closure $p)
    {
        return $this->collection->forAll($p);
    }

    /**
     * {@inheritdoc}
     */
    public function partition(\Closure $p)
    {
        return $this->collection->partition($p);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->collection->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function __sleep()
    {
        return $this->collection->__sleep();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->collection->offsetGet($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        return $this->collection->offsetSet($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->collection->offsetUnset($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->collection->key();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->collection->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return $this->collection->next();
    }

    /**
     * {@inheritdoc}
     */
    public function unwrap()
    {
        return $this->collection->unwrap();
    }

    /**
     * {@inheritdoc}
     */
    public function __clone()
    {
        clone $this->collection;
    }
}
