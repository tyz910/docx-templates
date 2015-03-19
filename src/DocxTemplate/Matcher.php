<?php
namespace DocxTemplate;

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
     * @param string $markPattern
     */
    public function __construct($markPattern = self::MARK_DEFAULT_PATTERN)
    {
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
     * @param  string $value
     * @param  string $text
     * @return string
     */
    public function replaceMark($key, $value, $text)
    {
        $pattern = sprintf($this->replaceRegexp, preg_quote($key));
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');

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
}
