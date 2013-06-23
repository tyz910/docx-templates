<?php
namespace DocxTemplate;

class Document
{
    const DOCUMENT_XML_URI = 'word/document.xml';

    /**
     * @var \ZipArchive
     */
    private $zip;

    /**
     * @var string
     */
    private $origFilePath;

    /**
     * @var string
     */
    private $tmpFilePath;

    /**
     * @param string $filePath
     * @throws Exception\Zip\FileCopyException
     */
    public function __construct($filePath)
    {
        $this->origFilePath = realpath($filePath);
        $this->tmpFilePath = sys_get_temp_dir() . '/' . uniqid('docx');

        if (copy($this->origFilePath, $this->tmpFilePath)) {
            $this->zip = new \ZipArchive();
            $this->zip->open($this->tmpFilePath);
        } else {
            throw new Exception\Zip\FileCopyException($this->origFilePath, $this->tmpFilePath);
        }
    }

    /**
     * @param string $filePath
     * @throws Exception\Zip\FileSaveException
     */
    public function save($filePath = null)
    {
        if ($filePath === null) {
            $filePath = $this->origFilePath;
        }

        if (file_exists($filePath)) {
            if (unlink($filePath) == false) {
                throw new Exception\Zip\FileSaveException($filePath, 'unlink error');
            }
        }

        if ($this->zip->close() === false) {
            throw new Exception\Zip\FileSaveException($filePath, 'could not close zip file');
        }

        if (rename($this->tmpFilePath, $filePath) == false) {
            throw new Exception\Zip\FileSaveException($filePath, 'rename error');
        }
    }

    /**
     * @param string $uri
     * @throws Exception\Zip\ContentReadException
     * @return string
     */
    public function getContent($uri = self::DOCUMENT_XML_URI)
    {
        $content = $this->zip->getFromName($uri);

        if ($content === false) {
            throw new Exception\Zip\ContentReadException($uri);
        } else {
            return $content;
        }
    }

    /**
     * @param string $content
     * @param string $uri
     * @throws \Exception
     * @return $this
     */
    public function setContent($content, $uri = self::DOCUMENT_XML_URI)
    {
        $result = $this->zip->addFromString($uri, $content);

        if ($result === false) {
            throw new Exception\Zip\ContentWriteException($uri, $content);
        } else {
            return $this;
        }
    }
}