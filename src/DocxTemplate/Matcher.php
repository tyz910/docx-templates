<?php
namespace DocxTemplate;

use DocxTemplate\Content\ContentInterface;

class Matcher
{
    const MARK_DEFAULT_PATTERN = '{{%s}}'; // how to display mark in template (printf format, where %s - mark name)
    const MARK_NAME_REGEXP = '[a-z0-9_]+'; // mark name limitations
    const EMPTY_REGEXP = '(<(?:(?!>[^>]+<).)*>|)'; // trash tags without content or empty

    /**
     * @var string
     */
    private $replaceRegexp;

    /**
     * @var string
     */
    private $markPattern;

    /**
     * @param string $markPattern
     */
    public function __construct($markPattern = self::MARK_DEFAULT_PATTERN)
    {
        $this->markPattern = $markPattern;
        $this->replaceRegexp = $this->convertPattern($markPattern);
    }

    /**
     * @param  string $pattern
     * @return string
     */
    private function convertPattern($pattern)
    {
        list($open, $close) = explode('%s', $pattern);

        $regexp =
            '/'
            .'( |)' // for save space if after replacement it will be on the end of tag without xml:space="preserve"
            .preg_quote($open)
            .self::EMPTY_REGEXP
            .'%s'
            .self::EMPTY_REGEXP
            .preg_quote($close)
            .'/u'
        ;

        return $regexp;
    }

    /**
     * @param  string $key
     * @param  string|ContentInterface $value
     * @param  string $text
     * @return string
     */
    public function replaceMark($key, $value, $text)
    {
        if ($value instanceof ContentInterface) {
            $value = $value->getContent();
        } else {
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
        }

        $pattern = sprintf($this->replaceRegexp, preg_quote($key));
        $replace = function ($matches) use ($value) {
            $space = $matches[1] == ' ' ? '&#160;' : ''; /** save space @see convertPattern */

            return $matches[2] . $space . $value . $matches[3];
        };

        return preg_replace_callback($pattern, $replace, $text);
    }

    /**
     * @param  string[] $vars
     * @param  string   $text
     * @return string
     */
    public function replaceMarks(array $vars, $text)
    {
        foreach ($vars as $key => $value) {
            $text = $this->replaceMark($key, $value, $text);
        }

        return $text;
    }

    /**
     * @param  string   $text
     * @return string[]
     */
    public function getMarks($text)
    {
        $pattern = sprintf($this->replaceRegexp, '(?P<mark>' . self::MARK_NAME_REGEXP . ')');
        preg_match_all($pattern, $text, $matches);

        return array_unique($matches['mark']);
    }

    /**
     * @param string $fromMark
     * @param string $toMark
     * @param string $placeMark
     * @param string $text
     * @return string
     */
    public function extractRange($fromMark, $toMark, $placeMark, &$text)
    {
        $uniqId = uniqid();
        $tmpReplace = [
            $fromMark => "MARK_" . $uniqId,
        ];
        if ($toMark != $fromMark) {
            $tmpReplace[$toMark] = "MARK_" . $uniqId;
        }
        $text = $this->replaceMarks($tmpReplace, $text);

        $pattern = "/MARK_{$uniqId}(.*)MARK_{$uniqId}/";
        $rangeContent = "";
        $text = preg_replace_callback($pattern, function ($matches) use (&$rangeContent, $placeMark) {
            if (isset($matches[1])) {
                $rangeContent = $matches[1];
            }

            return $this->toMark($placeMark);
        }, $text);

        return $rangeContent;
    }

    /**
     * @param string $name
     * @return string
     */
    public function toMark($name)
    {
        return sprintf($this->markPattern, $name);
    }
}
