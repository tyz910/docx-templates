<?php
namespace DocxTemplate\Exception\Zip;

class FileCopyException extends ZipException
{
    /**
     * @param string $from
     * @param string $to
     */
    public function __construct($from, $to)
    {
        $message = sprintf("Failed to copy file from '%s' to '%s'.", $from, $to);
        parent::__construct($message);
    }
}
