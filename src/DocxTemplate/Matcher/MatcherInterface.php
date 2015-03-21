<?php
namespace DocxTemplate\Matcher;

use DocxTemplate\Content\ContentInterface;

interface MatcherInterface
{
    /**
     * @param  string $mark
     * @param  string|ContentInterface $value
     * @param  string $text
     * @return string
     */
    public function replaceMark($mark, $value, $text);

    /**
     * @param  string   $text
     * @return string[]
     */
    public function getMarks($text);

    /**
     * @param string $fromMark
     * @param string $toMark
     * @param string $placeMark
     * @param string $text
     * @return string
     */
    public function extractRange($fromMark, $toMark, $placeMark, &$text);
}
