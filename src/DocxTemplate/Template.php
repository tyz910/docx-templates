<?php
namespace DocxTemplate;

use DocxTemplate\Content\DocBlock;

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
     * @param string|string[] $key
     * @param bool|string $placeMark
     * @return DocBlock
     */
    public function extractBlock($key, $placeMark = true)
    {
        if (is_array($key)) {
            $blockStart = $key[0];
            $blockEnd = $key[1];
        } else {
            $blockStart = $key . "_start";
            $blockEnd = $key . "_end";
        }

        if (is_bool($placeMark) && $placeMark) {
            $placeMark = is_string($key) ? $key : $blockStart;
        }

        $uniqId = uniqid();
        $this->assign([
            $blockStart => "BLOCK_OPEN" . $uniqId,
            $blockEnd   => "BLOCK_CLOSE" . $uniqId
        ]);

        $pattern = "/BLOCK_OPEN{$uniqId}(.*)BLOCK_CLOSE{$uniqId}/";
        $blockContent = "";
        $content = preg_replace_callback($pattern, function ($matches) use (&$blockContent, $placeMark) {
            if (isset($matches[1])) {
                $blockContent = $matches[1];
            }

            if ($placeMark) {
                return $this->matcher->toMark($placeMark);
            } else {
                return "";
            }

        }, $this->doc->getContent());

        $this->doc->setContent($content);

        // todo clean $blockContent

        return new DocBlock($blockContent);
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
