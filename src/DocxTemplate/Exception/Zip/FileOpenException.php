<?php
namespace DocxTemplate\Exception\Zip;

class FileOpenException extends ZipException
{
    /**
     * @var string[]
     */
    private static $errors = [
        \ZipArchive::ER_EXISTS => 'File already exists',
        \ZipArchive::ER_INCONS => 'Zip archive inconsistent',
        \ZipArchive::ER_INVAL  => 'Invalid argument',
        \ZipArchive::ER_MEMORY => 'Malloc failure',
        \ZipArchive::ER_NOENT  => 'No such file',
        \ZipArchive::ER_NOZIP  => 'Not a zip archive',
        \ZipArchive::ER_OPEN   => 'Can\'t open file',
        \ZipArchive::ER_READ   => 'Read error',
        \ZipArchive::ER_SEEK   => 'Seek error'
    ];

    /**
     * @param string $filePath
     * @param string $code
     */
    public function __construct($filePath, $code)
    {
        if (isset(self::$errors[$code])) {
            $msg = self::$errors[$code];
        } else {
            $msg = sprintf("Unknown error - '%s'", $code);
        }

        parent::__construct(sprintf("Failed to open file '%s'. %s.", $filePath, $msg));
    }
}
