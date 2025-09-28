<?php

namespace Core;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $scheme = '';
    private string $userInfo = '';
    private string $host = '';
    private ?int $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function __construct(string $uri = '')
    {
        if ($uri !== '') {
            $this->parseUri($uri);
        }
    }

    private function parseUri(string $uri): void
    {
        $parts = parse_url($uri);

        if ($parts === false) {
            throw new \InvalidArgumentException("Unable to parse URI: $uri");
        }

        $this->scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : '';
        $this->host = isset($parts['host']) ? strtolower($parts['host']) : '';
        $this->port = $parts['port'] ?? null;
        $this->path = $parts['path'] ?? '';
        $this->query = $parts['query'] ?? '';
        $this->fragment = $parts['fragment'] ?? '';

        // Обрабатываем user info
        $user = $parts['user'] ?? '';
        $pass = $parts['pass'] ?? '';

        if ($user !== '') {
            $this->userInfo = $user;
            if ($pass !== '') {
                $this->userInfo .= ':' . $pass;
            }
        }
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        if ($this->host === '') {
            return '';
        }

        $authority = $this->host;

        if ($this->userInfo !== '') {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->getStandardPort() === $this->port ? null : $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme($scheme): UriInterface
    {
        $scheme = strtolower($scheme);

        if ($this->scheme === $scheme) {
            return $this;
        }

        $clone = clone $this;
        $clone->scheme = $scheme;
        return $clone;
    }

    public function withUserInfo($user, $password = null): UriInterface
    {
        $userInfo = $user;
        if ($password !== null && $password !== '') {
            $userInfo .= ':' . $password;
        }

        if ($this->userInfo === $userInfo) {
            return $this;
        }

        $clone = clone $this;
        $clone->userInfo = $userInfo;
        return $clone;
    }

    public function withHost($host): UriInterface
    {
        $host = strtolower($host);

        if ($this->host === $host) {
            return $this;
        }

        $clone = clone $this;
        $clone->host = $host;
        return $clone;
    }

    public function withPort($port): UriInterface
    {
        if ($port !== null) {
            if (!is_int($port) || $port < 1 || $port > 65535) {
                throw new \InvalidArgumentException('Invalid port: ' . $port);
            }
        }

        if ($this->port === $port) {
            return $this;
        }

        $clone = clone $this;
        $clone->port = $port;
        return $clone;
    }

    public function withPath($path): UriInterface
    {
        if ($this->path === $path) {
            return $this;
        }

        $clone = clone $this;
        $clone->path = $path;
        return $clone;
    }

    public function withQuery($query): UriInterface
    {
        if ($this->query === $query) {
            return $this;
        }

        $clone = clone $this;
        $clone->query = $query;
        return $clone;
    }

    public function withFragment($fragment): UriInterface
    {
        if ($this->fragment === $fragment) {
            return $this;
        }

        $clone = clone $this;
        $clone->fragment = $fragment;
        return $clone;
    }

    public function __toString(): string
    {
        $uri = '';

        if ($this->scheme !== '') {
            $uri .= $this->scheme . ':';
        }

        $authority = $this->getAuthority();
        if ($authority !== '') {
            $uri .= '//' . $authority;
        }

        if ($this->path !== '') {
            if ($authority !== '' && $this->path[0] !== '/') {
                $uri .= '/' . $this->path;
            } else {
                $uri .= $this->path;
            }
        }

        if ($this->query !== '') {
            $uri .= '?' . $this->query;
        }

        if ($this->fragment !== '') {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }

    private function getStandardPort(): ?int
    {
        $standardPorts = [
            'http' => 80,
            'https' => 443,
            'ftp' => 21,
        ];

        return $standardPorts[$this->scheme] ?? null;
    }
}