<?php
namespace Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    use MessageTrait;
    private int $code = 200;
    private string $default_phase = '';
    public function __construct(
        StreamInterface $body,
        array $headers = [],
        string $protocol_version = '1.1',
        int $code = 200,
        string $default_phase = ''
    )
    {
        $this->body = $body;
        $this->protocol_version = $protocol_version;
        $this->headers = $headers;
        $this->code = $code;
        $this->default_phase = $default_phase;
    }

    public function getStatusCode() : int
    {
        return $this->code;
    }

    public function withStatus(int $code, string $reasonPhrase = '') : ResponseInterface
    {
        $clone = clone $this;
        $clone->code = $code;
        $clone->default_phase = $reasonPhrase;
        return $clone;
    }

    public function getReasonPhrase() : string
    {
        return $this->default_phase;
    }
    public function send()
    {
        http_response_code($this->getStatusCode());

        foreach ($this->getHeaders() as $name => $value)
        {
            header(sprintf('%s: %s', $name, implode(', ', $value)));
        }
        echo $this->getBody()->getContents();
    }
}
?>