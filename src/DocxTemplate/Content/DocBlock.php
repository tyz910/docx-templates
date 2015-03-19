<?php
namespace DocxTemplate\Content;

class DocBlock implements UnescapedValueInterface
{
    /**
     * @var string
     */
    private $content;

    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getUnescapedValue()
    {
        return $this->content;
    }
}