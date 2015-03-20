<?php
namespace DocxTemplate\Content;

use DocxTemplate\Matcher;

class MarkedContent extends Content
{
    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * @param string $content
     * @param Matcher $matcher
     */
    public function __construct($content, Matcher $matcher)
    {
        parent::__construct($content);
        $this->matcher = $matcher;
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
     * @param string $name
     * @return MarkedContent
     */
    public function extractContent($name)
    {
        $content = $this->getContent();
        $uniqName = uniqid($name);
        $extracted = $this->matcher->extractRange($name . "_start", $name . "_end", $uniqName, $content);
        $this->setContent($content);

        $content = new MarkedContent($extracted, $this->matcher);
        $content->bindTo($uniqName, $this);
        return $content;
    }

    /**
     * @return string[]
     */
    public function getMarks()
    {
        return $this->matcher->getMarks($this->getContent());
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function assignVar($key, $value)
    {
        $this->setContent(
            $this->matcher->replaceMark($key, $value, $this->getContent())
        );
    }
}
