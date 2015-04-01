<?php
namespace DocxTemplate\Matcher;

use DocxTemplate\Content\ContentInterface;

class RegExpMatcher implements MatcherInterface
{
    const DEFAULT_MARK_PREFIX = "{{";
    const DEFAULT_MARK_SUFFIX = "}}";
    const DEFAULT_MARK_NAME_REGEXP = '[a-z0-9_]+';
    const EMPTY_REGEXP = '(?:<[^>]+>)*'; // tags without content or empty

    /**
     * @var string
     */
    protected $markPrefix;

    /**
     * @var string
     */
    protected $markSuffix;

    /**
     * @var string
     */
    protected $markPrefixRegExp;

    /**
     * @var string
     */
    protected $markSuffixRegExp;

    /**
     * @var string
     */
    protected $markNameRegExp;

    /**
     * @param string $prefix
     * @param string $suffix
     * @param bool $strictMatch
     * @param string $markNameRegExp
     */
    public function __construct($prefix = self::DEFAULT_MARK_PREFIX, $suffix = self::DEFAULT_MARK_SUFFIX, $strictMatch = false, $markNameRegExp = null)
    {
        $this->markPrefix = $prefix;
        $this->markSuffix = $suffix;

        if ($this->markPrefix) {
            if ($strictMatch) {
                $this->markPrefixRegExp = preg_quote($this->markPrefix, "/");
            } else {
                $this->markPrefixRegExp = preg_quote($this->markPrefix, "/") . self::EMPTY_REGEXP;
            }
        }

        if ($this->markSuffix) {
            if ($strictMatch) {
                $this->markSuffixRegExp = preg_quote($this->markSuffix, "/");
            } else {
                $this->markSuffixRegExp = self::EMPTY_REGEXP . preg_quote($this->markSuffix, "/");
            }
        }

        $this->markNameRegExp = $markNameRegExp ? $markNameRegExp : self::DEFAULT_MARK_NAME_REGEXP;
    }

    /**
     * @param string $name
     * @param bool $escape
     * @return string
     */
    protected function getMarkRegExp($name, $escape = true)
    {
        return $this->markPrefixRegExp . ($escape ? preg_quote($name, "/") : $name) . $this->markSuffixRegExp;
    }

    /**
     * @param  string $mark
     * @param  string|ContentInterface $value
     * @param  string $text
     * @return string
     */
    public function replaceMark($mark, $value, $text)
    {
        if ($value instanceof ContentInterface) {
            $value = $value->getContent();
        } else {
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
        }

        return preg_replace('/' . $this->getMarkRegExp($mark) . '/u', $value, $text);
    }

    /**
     * @param  string   $text
     * @return string[]
     */
    public function getMarks($text)
    {
        $pattern = '/' . $this->getMarkRegExp('(?P<mark>' . self::DEFAULT_MARK_NAME_REGEXP . ')', false) . '/';
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
        $from = $this->getMarkRegExp($fromMark);
        $to = $this->getMarkRegExp($toMark);
        $rangeContent = "";

        $text = preg_replace_callback("/{$from}(.*){$to}/", function ($matches) use (&$rangeContent, $placeMark) {
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
    protected function toMark($name)
    {
        return $this->markPrefix . $name . $this->markSuffix;
    }
}
