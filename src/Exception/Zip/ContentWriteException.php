<?php
namespace DocxTemplate\Exception\Zip;

class ContentWriteException extends ZipException
{
    /**
     * @param string $uri
     * @param int    $content
     */
    public function __construct($uri, $content)
    {
        parent::__construct(sprintf("Failed to write content to uri: '%s'. Content:\r\n%s", $uri, $content));
    }
}
