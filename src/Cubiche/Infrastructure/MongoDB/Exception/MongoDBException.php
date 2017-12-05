<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Exception;

/**
 * MongoDBException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBException extends \Exception
{
    /**
     * @param string $documentName
     *
     * @return MongoDBException
     */
    public static function mappingNotFound($documentName)
    {
        return new self(sprintf('There is not mapping file associated with the "%s" document.', $documentName));
    }

    /**
     * @param string $documentName
     *
     * @return MongoDBException
     */
    public static function documentNotMappedToCollection($documentName)
    {
        return new self(sprintf('The "%s" document is not mapped to a MongoDB database collection.', $documentName));
    }

    /**
     * @param string $collectionName
     *
     * @return MongoDBException
     */
    public static function documentNameNotFound($collectionName)
    {
        return new self(sprintf(
            'There is not document mapped to the MongoDB database collection "%s".',
            $collectionName
        ));
    }
}
