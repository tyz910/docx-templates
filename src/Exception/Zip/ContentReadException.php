<?php
namespace DocxTemplate\Exception\Zip;

class ContentReadException extends ZipException
{
    /**
     * @param string $uri
     */
    public function __construct($uri)
    {
        parent::__construct(sprintf("Failed to read content from uri: '%s'.", $uri));
    }
}
