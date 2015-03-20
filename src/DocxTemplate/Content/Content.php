<?php
namespace DocxTemplate\Content;

class Content implements ContentInterface
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var MarkedContent
     */
    private $bindedTo;

    /**
     * @var string
     */
    private $bindedMark;

    /**
     * @param string $content
     */
    public function __construct($content)
    {
        $this->setContent($content);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $mark
     * @param MarkedContent $content
     */
    public function bindTo($mark, MarkedContent $content)
    {
        $this->bindedMark = $mark;
        $this->bindedTo = $content;
    }

    /**
     * @return MarkedContent
     */
    public function getBindedTo()
    {
        return $this->bindedTo;
    }

    /**
     * @return string
     */
    public function getBindedMark()
    {
        return $this->bindedMark;
    }
}
