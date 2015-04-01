<?php
namespace DocxTemplate\Exception\Zip;

class FileCloseException extends ZipException
{
    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        parent::__construct(sprintf("Failed to close file '%s'.", $filePath));
    }
}
