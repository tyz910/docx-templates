<?php
namespace DocxTemplate;

use DocxTemplate\Content\ContentInterface;

class Matcher
{
    const DEFAULT_OPEN = "{{";
    const DEFAULT_CLOSE = "}}";
    const DEFAULT_MARK_NAME_REGEXP = '[a-z0-9_]+';
    const EMPTY_REGEXP = '(?:<[^>]+>)*'; // tags without content or empty

    /**
     * @var string
     */
    private $open;

    /**
     * @var string
     */
    private $close;

    /**
     * @var string
     */
    private $openRegExp;

    /**
     * @var string
     */
    private $closeRegExp;

    /**
     * @var string
     */
    private $markNameRegExp;

    /**
     * @param string $open
     * @param string $close
     * @param bool $strictMatch
     * @param string $markNameRegExp
     */
    public function __construct($open = self::DEFAULT_OPEN, $close = self::DEFAULT_CLOSE, $strictMatch = false, $markNameRegExp = null)
    {
        $this->open = $open;
        $this->close = $close;

        if ($this->open) {
            if ($strictMatch) {
                $this->openRegExp = preg_quote($this->open, "/");
            } else {
                $this->openRegExp = preg_quote($this->open, "/") . self::EMPTY_REGEXP;
            }
        }

        if ($this->close) {
            if ($strictMatch) {
                $this->closeRegExp = preg_quote($this->close, "/");
            } else {
                $this->closeRegExp = self::EMPTY_REGEXP . preg_quote($this->close, "/");
            }
        }

        $this->markNameRegExp = $markNameRegExp ? $markNameRegExp : self::DEFAULT_MARK_NAME_REGEXP;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getMarkRegExp($name)
    {
        return $this->openRegExp . $name . $this->closeRegExp;
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

        return preg_replace('/' . $this->getMarkRegExp($key) . '/u', $value, $text);
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
        $pattern = $this->getMarkRegExp('(?P<mark>' . self::DEFAULT_MARK_NAME_REGEXP . ')');
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
        $pattern = "/{$from}(.*){$to}/";
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
        return $this->open . $name . $this->close;
    }
}
