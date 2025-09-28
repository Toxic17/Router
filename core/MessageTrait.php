<?php
    namespace Core;
    use Psr\Http\Message\MessageInterface;
    use Psr\Http\Message\StreamInterface;

    trait MessageTrait
    {
        private string $protocol_version = '1.1';
        private array $headers = [];
        private StreamInterface $body;

        public function getProtocolVersion() : string
        {
            return $this->protocol_version;
        }

        public function withProtocolVersion(string $version) : MessageInterface
        {
            $clone = $this;
            $clone->protocol_version = $version;
            return $clone;
        }
        public function getHeaders() : array
        {
            return $this->headers;
        }
        public function hasHeader($name) : bool
        {
            return isset($this->headers[strtolower($name)]);
        }
        public function getHeader($name) : array
        {
            return $this->headers[strtolower($name)] ?? [];
        }
        public function getHeaderLine($name) : string
        {
            return implode(',',$this->getHeader($name));
        }
        public function withHeader($name,$value) : MessageInterface
        {
            $clone = clone $this;
            $clone->headers[strtolower($name)] = is_array($value) ? $value : [$value];
            return $clone;
        }
        public function withAddedHeader($name,$value) : MessageInterface
        {
            $clone = clone $this;
            if(isset($clone->headers[strtolower($name)])){
                $clone->headers[strtolower($name)] = array_merge(
                    $clone->headers[strtolower($name)],
                    is_array($value) ? $value : [$value]
                );
            }
            else
            {
                $clone->headers[strtolower($name)] = is_array($value) ? $value : [$value];
            }
            return $clone;
        }
        public function withoutHeader(string $name) : MessageInterface
        {
            $clone = clone $this;
            unset($clone->headers[strtolower($name)]);
            return $clone;
        }
        public function getBody() : StreamInterface
        {
            return $this->body;
        }
        public function withBody(StreamInterface $body) : MessageInterface
        {
            $clone = clone $this;
            $clone->body = $body;
            return $clone;
        }
    }
?>