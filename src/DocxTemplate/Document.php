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
    private $filePath;

    /**
     * @var array
     */
    private $readCache = [];

    /**
     * @var array
     */
    private $writeCache = [];

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->zip = new \ZipArchive();
    }

    /**
     * @param string $filePath
     * @return $this
     * @throws Exception\Zip\FileOpenException
     */
    private function zipOpen($filePath)
    {
        if ($code = $this->zip->open($filePath) !== true) {
            throw new Exception\Zip\FileOpenException($filePath, $code);
        }
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @return \ZipArchive
     */
    private function getZip()
    {
        if (!$this->zip->filename) {
            $this->zipOpen($this->filePath);
        }

        return $this->zip;
    }

    /**
     * @return $this
     * @throws Exception\Zip\FileCloseException
     */
    private function zipClose()
    {
        if ($this->getZip()->close() === false) {
            throw new Exception\Zip\FileCloseException($this->zip->filename);
        }

        return $this;
    }

    /**
     * @param string $newFilePath
     * @return $this
     * @throws Exception\Zip\FileCopyException
     */
    private function zipCopy($newFilePath)
    {
        $this->zipClose();
        $origFilePath = $this->filePath;

        if (copy($origFilePath, $newFilePath)) {
            $this->zipOpen($newFilePath);
        } else {
            throw new Exception\Zip\FileCopyException($origFilePath, $newFilePath);
        }

        return $this;
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function save($filePath = null)
    {
        if ($filePath) {
            $this->zipCopy($filePath);
        }

        foreach ($this->writeCache as $uri => $content) {
            $this->writeContent($content, $uri);
        }

        $this->zipClose();
        $this->readCache = $this->writeCache = [];

        return $this;
    }

    /**
     * @param  string $uri
     * @throws Exception\Zip\ContentReadException
     * @return string
     */
    public function getContent($uri = self::DOCUMENT_XML_URI)
    {
        if (!array_key_exists($uri, $this->readCache)) {
            $content = $this->getZip()->getFromName($uri);
            if ($content === false) {
                throw new Exception\Zip\ContentReadException($uri);
            }

            $this->readCache[$uri] = $content;
        }

        return $this->readCache[$uri];
    }

    /**
     * @param string $content
     * @param string $uri
     * @return $this
     */
    public function setContent($content, $uri = self::DOCUMENT_XML_URI)
    {
        $this->readCache[$uri] = $this->writeCache[$uri] = $content;
        return $this;
    }

    /**
     * @param string $content
     * @param string $uri
     * @throws Exception\Zip\ContentWriteException
     * @return $this
     */
    private function writeContent($content, $uri)
    {
        $result = $this->getZip()->addFromString($uri, $content);

        if ($result === false) {
            throw new Exception\Zip\ContentWriteException($uri, $content);
        } else {
            return $this;
        }
    }
}
