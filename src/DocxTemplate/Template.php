<?php
namespace DocxTemplate;

class Template
{
    /**
     * @var Document
     */
    private $doc;

    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * @param Document $doc
     * @param Matcher  $matcher
     */
    public function __construct(Document $doc, Matcher $matcher)
    {
        $this->doc = $doc;
        $this->matcher = $matcher;
    }

    /**
     * @param string $filePath
     */
    public function save($filePath = null)
    {
        $this->doc->save($filePath);
    }

    /**
     * @return string[]
     */
    public function getMarks()
    {
        return $this->matcher->getMarks($this->doc->getContent());
    }

    public function removeMarks()
    {
        foreach ($this->getMarks() as $mark) {
            $this->assign($mark, '');
        }
    }

    /**
     * @return bool
     */
    public function isFilled()
    {
        return ! (bool) $this->getMarks();
    }

    /**
     * @param string|string[] $key
     * @param string|null     $value
     * @return $this
     */
    public function assign($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->assignVar($k, $v);
            }
        } else {
            $this->assignVar($key, $value);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function assignVar($key, $value)
    {
        $this->doc->setContent(
            $this->matcher->replaceMark($key, $value, $this->doc->getContent())
        );
    }
}
