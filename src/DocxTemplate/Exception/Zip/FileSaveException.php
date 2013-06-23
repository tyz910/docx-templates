<?php
namespace DocxTemplate\Exception\Zip;

class FileSaveException extends ZipException
{
    /**
     * @param string $filePath
     * @param string $reason
     */
    public function __construct($filePath, $reason = null)
    {
        $message = sprintf("Failed to save file to '%s'", $filePath);

        if ($reason) {
            $message .= sprintf('. Reason: %s', $reason);
        }

        parent::__construct($message);
    }
}