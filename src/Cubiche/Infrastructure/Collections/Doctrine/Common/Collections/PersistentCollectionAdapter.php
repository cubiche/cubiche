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

use Cubiche\Core\Collections\CollectionInterface;
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
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::setDocumentManager()
     */
    public function setDocumentManager(DocumentManager $dm)
    {
        $this->collection->setDocumentManager($dm);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::setMongoData()
     */
    public function setMongoData(array $mongoData)
    {
        $this->collection->setMongoData($mongoData);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getMongoData()
     */
    public function getMongoData()
    {
        return $this->collection->getMongoData();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::setHints()
     */
    public function setHints(array $hints)
    {
        $this->collection->setHints($hints);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getHints()
     */
    public function getHints()
    {
        return $this->collection->getHints();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::initialize()
     */
    public function initialize()
    {
        $this->collection->initialize();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::isDirty()
     */
    public function isDirty()
    {
        return $this->collection->isDirty();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::setDirty()
     */
    public function setDirty($dirty)
    {
        $this->collection->setDirty($dirty);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::setOwner()
     */
    public function setOwner($document, array $mapping)
    {
        $this->collection->setOwner($document, $mapping);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::takeSnapshot()
     */
    public function takeSnapshot()
    {
        $this->collection->takeSnapshot();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::clearSnapshot()
     */
    public function clearSnapshot()
    {
        $this->collection->clearSnapshot();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getSnapshot()
     */
    public function getSnapshot()
    {
        return $this->collection->getSnapshot();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getDeleteDiff()
     */
    public function getDeleteDiff()
    {
        return $this->collection->getDeleteDiff();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getDeletedDocuments()
     */
    public function getDeletedDocuments()
    {
        return $this->collection->getDeletedDocuments();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getInsertDiff()
     */
    public function getInsertDiff()
    {
        return $this->collection->getInsertDiff();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getOwner()
     */
    public function getOwner()
    {
        return $this->collection->getOwner();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getMapping()
     */
    public function getMapping()
    {
        return $this->collection->getMapping();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getTypeClass()
     */
    public function getTypeClass()
    {
        return $this->collection->getTypeClass();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::setInitialized()
     */
    public function setInitialized($bool)
    {
        $this->collection->setInitialized($bool);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::isInitialized()
     */
    public function isInitialized()
    {
        return $this->collection->isInitialized();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::first()
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::last()
     */
    public function last()
    {
        return $this->collection->last();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::remove()
     */
    public function remove($key)
    {
        return $this->collection->remove($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::removeElement()
     */
    public function removeElement($element)
    {
        return $this->collection->removeElement($element);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::containsKey()
     */
    public function containsKey($key)
    {
        return $this->collection->containsKey($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::contains()
     */
    public function contains($element)
    {
        return $this->collection->contains($element);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::exists()
     */
    public function exists(\Closure $p)
    {
        return $this->collection->exists($p);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::indexOf()
     */
    public function indexOf($element)
    {
        return $this->collection->indexOf($element);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::get()
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getKeys()
     */
    public function getKeys()
    {
        return $this->collection->getKeys();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getValues()
     */
    public function getValues()
    {
        return $this->collection->getValues();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::count()
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::set()
     */
    public function set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::add()
     */
    public function add($value)
    {
        return $this->collection->add($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::isEmpty()
     */
    public function isEmpty()
    {
        return $this->collection->isEmpty();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::getIterator()
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::map()
     */
    public function map(\Closure $func)
    {
        return $this->collection->map($func);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::filter()
     */
    public function filter(\Closure $p)
    {
        return $this->collection->filter($p);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::forAll()
     */
    public function forAll(\Closure $p)
    {
        return $this->collection->forAll($p);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::partition()
     */
    public function partition(\Closure $p)
    {
        return $this->collection->partition($p);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::toArray()
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::clear()
     */
    public function clear()
    {
        $this->collection->clear();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::slice()
     */
    public function slice($offset, $length = null)
    {
        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::__sleep()
     */
    public function __sleep()
    {
        return $this->collection->__sleep();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::offsetExists()
     */
    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->collection->offsetGet($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        return $this->collection->offsetSet($offset, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        return $this->collection->offsetUnset($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::key()
     */
    public function key()
    {
        return $this->collection->key();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::current()
     */
    public function current()
    {
        return $this->collection->current();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::next()
     */
    public function next()
    {
        return $this->collection->next();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::unwrap()
     */
    public function unwrap()
    {
        return $this->collection->unwrap();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Doctrine\ODM\MongoDB\PersistentCollection::__clone()
     */
    public function __clone()
    {
        clone $this->collection;
    }
}
