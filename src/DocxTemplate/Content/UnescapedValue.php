<?php
namespace DocxTemplate\Content;

class UnescapedValue implements UnescapedValueInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getUnescapedValue()
    {
        return $this->value;
    }
}