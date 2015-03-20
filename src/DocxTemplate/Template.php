<?php
namespace DocxTemplate;

use DocxTemplate\Content\MarkedContent;

class Template
{
    /**
     * @var Document
     */
    private $doc;

    /**
     * @var MarkedContent
     */
    private $docContent;

    /**
     * @param Document $doc
     * @param Matcher  $matcher
     */
    public function __construct(Document $doc, Matcher $matcher)
    {
        $this->doc = $doc;
        $this->docContent = new MarkedContent($this->doc->getContent(), $matcher);
    }

    /**
     * @return Document
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * @return MarkedContent
     */
    public function getDocContent()
    {
        return $this->docContent;
    }

    /**
     * @param string $filePath
     */
    public function save($filePath = null)
    {
        $this->doc->setContent($this->docContent->getContent());
        $this->doc->save($filePath);
    }

    /**
     * @param string|string[] $key
     * @param string|null     $value
     * @return $this
     */
    public function assign($key, $value = null)
    {
        $this->docContent->assign($key, $value);

        return $this;
    }

    /**
     * @param string $name
     * @param string[][] $rows
     * @return $this
     */
    public function loop($name, $rows = [])
    {
        $this->docContent->loop($name, $rows);

        return $this;
    }
}
