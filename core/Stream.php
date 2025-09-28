<?php 
namespace Core;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
	private $resource;
    private $size;
    private $metadata;

    public function __construct($resourceOrPath, $mode = 'rb')
    {
        if (is_resource($resourceOrPath)) {
            $this->resource = $resourceOrPath;
        } else {
            $this->resource = fopen($resourceOrPath, $mode);
        }

        $stat = fstat($this->resource);
        $this->size = isset($stat['size']) ? $stat['size'] : null;

        $this->metadata = stream_get_meta_data($this->resource);
    }

    public function close() : void
    {
        fclose($this->resource);
    }

    public function tell() : int
    {
        return ftell($this->resource);
    }

    public function seek($offset, $whence = SEEK_SET) : void
    {
        fseek($this->resource, $offset, $whence) !== -1;
    }

    public function eof() : bool
    {
        return feof($this->resource);
    }

    public function read($length) : string
    {
        return fread($this->resource, $length);
    }

    public function getContents() : string
    {
        return stream_get_contents($this->resource);
    }

    public function write($string) : int
    {
        return fwrite($this->resource, $string);
    }

    public function isReadable() : bool
    {
        return in_array($this->metadata['mode'], ['r', 'w+', 'a+', 'x+', 'c+']);
    }

    public function isWritable() : bool
    {
        return in_array($this->metadata['mode'], ['w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+']);
    }

    public function getSize() :? int
    {
        return $this->size;
    }

    public function rewind() : void
    {
        rewind($this->resource);
    }

    public function __toString()
    {
        try {
            return $this->getContents();
        } catch (\Throwable $e) {
            return '';
        }
    }

    public function getMetadata($key = null)
    {
        if ($key === null) {
            return $this->metadata;
        }
        return isset($this->metadata[$key]) ? $this->metadata[$key] : null;
    }

    public function isSeekable(): bool
    {
        return isset($this->metadata['seekable']) && $this->metadata['seekable'];
    }

    public function detach()
    {
        if (!$this->resource) {
            return null;
        }

        $result = $this->resource;
        $this->resource = null;
        $this->size = null;
        $this->metadata = [];
        return $result;
    }

}

?>