<?php
namespace DocxTemplate\Content;

use DocxTemplate\Content\Collection\LoopCollection;
use DocxTemplate\Matcher\MatcherInterface;

class MarkedContent extends Content
{
    /**
     * @var MatcherInterface
     */
    private $matcher;

    /**
     * @param string            $content
     * @param MatcherInterface  $matcher
     */
    public function __construct($content, MatcherInterface $matcher)
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
        $extracted = $this->matcher->extractRange($name, $name, $name, $content);
        $this->setContent($content);

        $content = new MarkedContent($extracted, $this->matcher);
        $content->bindTo($name, $this);
        return $content;
    }

    /**
     * @param string $name
     * @param string[][] $rows
     * @return $this
     */
    public function loop($name, $rows = [])
    {
        $block = $this->extractContent($name);
        $loop = new LoopCollection($block);

        foreach ($rows as $row) {
            $loop->iterate($row);
        }

        $loop->finish();
        return $this;
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
