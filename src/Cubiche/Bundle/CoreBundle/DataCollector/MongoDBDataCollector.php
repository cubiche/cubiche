<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Bundle\CoreBundle\DataCollector;

use Cubiche\Infrastructure\MongoDB\Common\QueryLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * MongoDBDataCollector class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MongoDBDataCollector extends DataCollector implements QueryLoggerInterface
{
    /**
     * @var array
     */
    protected $queries;

    /**
     * MongoDBDataCollector constructor.
     */
    public function __construct()
    {
        $this->queries = [];
    }

    /**
     * {@inheritdoc}
     */
    public function logQuery(array $query)
    {
        $this->queries[] = $query;
    }

    /**
     * @param Request         $request
     * @param Response        $response
     * @param \Exception|null $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['nb_queries'] = count($this->queries);
        $this->data['queries'] = array_map(function ($query) {
            switch ($query['operation']) {
                case 'BulkDelete':
                case 'BulkInsert':
                case 'BulkUpdate':
                    $queryString = '<span class="token">db</span><span class="token punctuation">.</span>';
                    $queryString .= '<span class="token keyword">'.$query['collection']->getCollectionName().'</span>';
                    $queryString .= '<span class="token punctuation">.</span>';
                    $queryString .= '<span class="token function">bulkWrite</span>(';
                    foreach ($query['parameters'] as $item) {
                        $queryString .= '<span class="token italic">';
                        $queryString .= json_encode($item, JSON_PRETTY_PRINT).'</span>, ';
                    }
                    $queryString = substr($queryString, 0, strlen($queryString) - 2).')';
                    break;
                case 'find':
                case 'deleteMany':
                case 'count':
                case 'aggregate':
                    $queryString = '<span class="token">db</span><span class="token punctuation">.</span>';
                    $queryString .= '<span class="token keyword">'.$query['collection']->getCollectionName().'</span>';
                    $queryString .= '<span class="token punctuation">.</span>';
                    $queryString .= '<span class="token function">'.$query['operation'].'</span>(';
                    foreach ($query['parameters'] as $item) {
                        $queryString .= '<span class="token italic">';
                        $queryString .= json_encode($item, JSON_PRETTY_PRINT).'</span>, ';
                    }
                    $queryString = substr($queryString, 0, strlen($queryString) - 2).'</span>)';
                    break;
                default:
                    break;
            }

            return $queryString;
        }, $this->queries);
    }

    /**
     * @return mixed
     */
    public function getQueryCount()
    {
        return $this->data['nb_queries'];
    }

    /**
     * @return mixed
     */
    public function getQueries()
    {
        return $this->data['queries'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cubiche.mongodb_collector';
    }

    /**
     * Resets this data collector to its initial state.
     */
    public function reset()
    {
        $this->data = array();
    }
}
