<?php
namespace DocxTemplate\Content\Collection;

use DocxTemplate\Content\MarkedContent;

class LoopCollection extends ContentCollection
{
    /**
     * @var MarkedContent
     */
    private $baseContent;

    /**
     * @var MarkedContent
     */
    private $currentItem;

    /**
     * @param MarkedContent $content
     */
    public function __construct(MarkedContent $content)
    {
        $this->baseContent = $content;
    }

    /**
     * @return MarkedContent
     */
    public function getBaseContent()
    {
        return $this->baseContent;
    }

    /**
     * @return MarkedContent
     */
    public function getCurrentItem()
    {
        return $this->currentItem;
    }

    /**
     * @return MarkedContent
     */
    public function itemStart()
    {
        if (!$this->currentItem) {
            $this->currentItem = clone $this->baseContent;
        }

        return $this->currentItem;
    }

    public function itemEnd()
    {
        if ($this->currentItem) {
            $this->addContent($this->currentItem);
            $this->currentItem = null;
        }
    }

    public function finish()
    {
        $this->baseContent->getBindedTo()->assign([
            $this->baseContent->getBindedMark() => $this
        ]);

        $this->clearContents();
    }
}
