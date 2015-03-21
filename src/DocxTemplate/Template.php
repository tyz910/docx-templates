<?php
namespace DocxTemplate;

use DocxTemplate\Content\MarkedContent;
use DocxTemplate\Matcher\MatcherInterface;

class Template
{
    /**
     * @var Document
     */
    private $doc;

    /**
     * @var MatcherInterface
     */
    private $matcher;

    /**
     * @var MarkedContent[]
     */
    private $docContents = [];

    /**
     * @param Document          $doc
     * @param MatcherInterface  $matcher
     */
    public function __construct(Document $doc, MatcherInterface $matcher)
    {
        $this->doc = $doc;
        $this->matcher = $matcher;
    }

    /**
     * @return Document
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * @param string $uri
     * @return MarkedContent
     */
    public function getDocContent($uri = Document::DOCUMENT_XML_URI)
    {
        if (!isset($this->docContents[$uri])) {
            $this->docContents[$uri] = new MarkedContent($this->doc->getContent($uri, true), $this->matcher);
        }

        return $this->docContents[$uri];
    }

    /**
     * @param string $filePath
     */
    public function save($filePath = null)
    {
        foreach ($this->docContents as $uri => $docContent) {
            $this->doc->setContent($docContent->getContent(), $uri);
        }

        $this->doc->save($filePath);
    }

    /**
     * @param string|string[] $key
     * @param string|null     $value
     * @return $this
     */
    public function assign($key, $value = null)
    {
        $this->getDocContent()->assign($key, $value);

        return $this;
    }

    /**
     * @param string $name
     * @param string[][] $rows
     * @return $this
     */
    public function loop($name, $rows = [])
    {
        $this->getDocContent()->loop($name, $rows);

        return $this;
    }
}
