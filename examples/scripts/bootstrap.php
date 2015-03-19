<?php
require __DIR__ . '/../../vendor/autoload.php';

class ExampleDoc
{
    const FOLDER_ORIGINAL  = "original";
    const FOLDER_PROCESSED = "processed";

    /**
     * @var string
     */
    private $docFileName;

    /**
     * @param string $docFileName
     */
    public function __construct($docFileName)
    {
        $this->docFileName = $docFileName;
    }

    /**
     * @return string
     */
    public function getOriginalPath()
    {
        return $this->getPath(self::FOLDER_ORIGINAL);
    }

    /**
     * @return string
     */
    public function getProcessedPath()
    {
        return $this->getPath(self::FOLDER_PROCESSED);
    }

    /**
     * @param string $folder
     * @return string
     */
    private function getPath($folder)
    {
        return __DIR__ . "/../docs/" . $folder . "/" . $this->docFileName;
    }
}
