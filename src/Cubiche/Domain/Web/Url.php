<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Web;

use Cubiche\Domain\System\StringLiteral;

/**
 * Url class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Url extends StringLiteral
{
    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var Host
     */
    protected $host;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var Port
     */
    protected $port;

    /**
     * @var string
     */
    protected $queryString;

    /**
     * @var string
     */
    protected $fragmentId;

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($url)
    {
        parent::__construct($url);

        $user = \parse_url($url, PHP_URL_USER);
        $this->user = $user ? new StringLiteral($user) : new StringLiteral('');
        $pass = \parse_url($url, PHP_URL_PASS);
        $this->password = $pass ? new StringLiteral($pass) : new StringLiteral('');
        $this->scheme = $this->parseScheme($url);
        $this->host = $this->parseHost($url);
        $this->path = $this->parsePath($url);
        $this->port = $this->parsePort($url);
        $this->queryString = $this->parseQueryString($url);
        $this->fragmentId = $this->parseFragmentIdentifier($url);

        $this->createUrl();
    }

    protected function createUrl()
    {
        $userPass = '';
        if ($this->user()->isEmpty() === false) {
            $userPass = \sprintf('%s@', $this->user());
            if ($this->password()->isEmpty() === false) {
                $userPass = \sprintf('%s:%s@', $this->user(), $this->password());
            }
        }
        $port = '';
        if ($this->port() !== null) {
            $port = \sprintf(':%d', $this->port()->toNative());
        }

        $this->value = \sprintf(
            '%s://%s%s%s%s%s%s',
            $this->scheme(),
            $userPass,
            $this->host(),
            $port,
            $this->path(),
            $this->queryString(),
            $this->fragmentId()
        );
    }

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function parseScheme($url)
    {
        $scheme = \parse_url($url, PHP_URL_SCHEME);
        if (\preg_match('/^[a-z]([a-z0-9\+\.-]+)?$/i', $scheme) === 0) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "schema".',
                $url
            ));
        }

        return new StringLiteral($scheme);
    }

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     *
     * @return Host
     */
    protected function parseHost($url)
    {
        $host = \parse_url($url, PHP_URL_HOST);

        return Host::fromNative($host);
    }

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     *
     * @return Path | null
     */
    protected function parsePath($url)
    {
        $path = \parse_url($url, PHP_URL_PATH);
        $filteredValue = parse_url($path, PHP_URL_PATH);
        if ($filteredValue === null || strlen($filteredValue) != strlen($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument "%s" is invalid. Allowed types for argument are "url".',
                $url
            ));
        }

        return new Path($filteredValue);
    }

    /**
     * @param string $url
     *
     * @return Port | NULL
     */
    protected function parsePort($url)
    {
        $port = \parse_url($url, PHP_URL_PORT);
        if ($port) {
            return new Port($port);
        }

        return;
    }

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function parseQueryString($url)
    {
        $queryString = \parse_url($url, PHP_URL_QUERY);
        if ($queryString) {
            $queryString = \sprintf('?%s', $queryString);

            return new StringLiteral($queryString);
        }

        return new StringLiteral('');
    }

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function parseFragmentIdentifier($url)
    {
        $fragmentId = \parse_url($url, PHP_URL_FRAGMENT);
        if ($fragmentId) {
            $fragment = \sprintf('#%s', $fragmentId);
            if (\preg_match('/^#[?%!$&\'()*+,;=a-zA-Z0-9-._~:@\/]*$/', $fragment) === 0) {
                throw new \InvalidArgumentException(sprintf(
                    'Argument "%s" is invalid. Allowed types for argument are "fragment identifier".',
                    $fragment
                ));
            }

            return new StringLiteral($fragment);
        }

        return new StringLiteral('');
    }

    /**
     * @return Host
     */
    public function host()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function fragmentId()
    {
        return $this->fragmentId;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function port()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function queryString()
    {
        return $this->queryString;
    }

    /**
     * @return string
     */
    public function scheme()
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function user()
    {
        return $this->user;
    }
}
