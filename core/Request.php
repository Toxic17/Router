<?php 
namespace Core;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;


class Request implements RequestInterface
{
    use MessageTrait;

    private string $request_target = '/';
    private string $request_method = 'GET';

    public function __construct($protocol_version, array $headers, StreamInterface $body,string $request_target,string $request_method,Uri $uri)
    {
        $this->body = $body;
        $this->protocol_version = $protocol_version;
        $this->headers = $headers;
        $this->request_target = $request_target;
        $this->request_method = $request_method;
        $this->uri = new Uri();
    }

    public function getRequestTarget() : string
    {
        return $this->request_target;
    }

    public function withRequestTarget(string $requestTarget) : RequestInterface
    {
        $clone = clone $this;
        $clone->request_target = $requestTarget;
        return $clone;
    }

    public function getMethod() : string
    {
        return $this->request_method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $clone = clone $this;
        $clone->request_method = $method;
        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        if ($this->uri === $uri) {
            return $this;
        }

        $clone = clone $this;
        $clone->uri = $uri;
        $clone->requestTarget = $clone->buildRequestTarget();

        if (!$preserveHost) {
            $host = $uri->getHost();
            if ($host !== '') {
                $port = $uri->getPort();
                if ($port !== null) {
                    $host .= ':' . $port;
                }
                $clone = $clone->withHeader('Host', $host);
            }
        }

        return $clone;
    }

    private function buildRequestTarget(): string
    {
        if ($this->request_target !== '') {
            return $this->request_target;
        }

        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }

        $query = $this->uri->getQuery();
        if ($query !== '') {
            $target .= '?' . $query;
        }

        return $target;
    }
}

?>