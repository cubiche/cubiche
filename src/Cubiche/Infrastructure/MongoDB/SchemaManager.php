<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB;

use Cubiche\Core\Metadata\ClassMetadataFactoryInterface;
use MongoDB\Database;

/**
 * SchemaManager class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SchemaManager
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * SchemaManager constructor.
     *
     * @param DocumentManager               $dm
     * @param ClassMetadataFactoryInterface $metadataFactory
     */
    public function __construct(DocumentManager $dm, ClassMetadataFactoryInterface $metadataFactory)
    {
        $this->dm = $dm;
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * Drop all the mapped document databases in the metadata factory.
     */
    public function dropDatabases()
    {
        foreach ($this->metadataFactory->getAllMetadata() as $class) {
            if ($class->getMetadata('collection') === null) {
                continue;
            }

            $this->dropDocumentDatabase($class->className());
        }
    }

    /**
     * Drop the document database for a mapped class.
     *
     * @param string $documentName
     *
     * @throws \InvalidArgumentException
     */
    public function dropDocumentDatabase($documentName)
    {
        $class = $this->dm->getClassMetadata($documentName);
        if ($class->getMetadata('collection') === null) {
            throw new \InvalidArgumentException('Cannot drop document database for entity documents.');
        }

        $this->dm->getDocumentDatabase($documentName)->drop();
    }
}
