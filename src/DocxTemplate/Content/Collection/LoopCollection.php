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
    private $currentIteration;

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
    public function getCurrentIteration()
    {
        return $this->currentIteration;
    }

    /**
     * @param array $values
     * @return MarkedContent
     */
    public function iterate(array $values = [])
    {
        if ($this->currentIteration) {
            $this->addContent($this->currentIteration);
        }

        $this->currentIteration = clone $this->baseContent;
        if ($values) {
            $this->currentIteration->assign($values);
        }

        return $this->currentIteration;
    }

    public function finish()
    {
        if ($this->currentIteration) {
            $this->addContent($this->currentIteration);
            $this->currentIteration = null;
        }

        $this->baseContent->getBindedTo()->assign([
            $this->baseContent->getBindedMark() => $this
        ]);

        $this->clearContents();
    }
}
