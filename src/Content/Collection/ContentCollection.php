<?php
namespace DocxTemplate\Content\Collection;

use DocxTemplate\Content\ContentInterface;

class ContentCollection implements ContentInterface
{
    /**
     * @var ContentInterface[]
     */
    private $contents = [];

    /**
     * @param ContentInterface $content
     * @param bool $clone
     * @return $this
     */
    public function addContent(ContentInterface $content, $clone = false)
    {
        if ($clone) {
            $this->contents[] = clone $content;
        } else {
            $this->contents[] = $content;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function clearContents()
    {
        $this->contents = [];
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return implode('', array_map(function (ContentInterface $content) {
            return $content->getContent();
        }, $this->contents));
    }
}
