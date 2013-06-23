<?php
namespace DocxTemplate;

class Template
{
    const DEFAULT_MARK_PATTERN = '{{%s}}';

    /**
     * @var Document
     */
    private $doc;

    /**
     * @var string
     */
    private $docContent;

    /**
     * @var string
     */
    private $markPattern;

    /**
     * @param Document $doc
     * @param string $markPattern
     */
    public function __construct(Document $doc, $markPattern = self::DEFAULT_MARK_PATTERN)
    {
        $this->doc = $doc;
        $this->docContent = $doc->getContent();
        $this->markPattern = $markPattern;
    }

    /**
     * @param string $filePath
     */
    public function save($filePath = null)
    {
        $this->doc->save($filePath);
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
            $this->assign($key, $value);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function assignVar($key, $value)
    {
        // todo
    }

    /**
     * @param string $from
     * @param string $to
     * @return $this
     */
    public function clearBlock($from, $to)
    {
        // todo
        return $this;
    }
}