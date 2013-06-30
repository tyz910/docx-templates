<?php
namespace DocxTemplate;

class Template
{
    const MARK_DEFAULT_PATTERN = '{{%s}}'; // how to display mark in template (printf format, where %s - mark name)
    const MARK_NAME_REGEXP = '[a-z0-9_]+'; // mark name limitations

    /**
     * @var Document
     */
    private $doc;

    /**
     * @var string
     */
    private $replaceRegexp;

    /**
     * @param Document $doc
     * @param string $markPattern
     */
    public function __construct(Document $doc, $markPattern = self::MARK_DEFAULT_PATTERN)
    {
        $this->doc = $doc;
        $this->replaceRegexp = $this->convertPattern($markPattern);
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function convertPattern($pattern)
    {
        list($open, $close) = explode('%s', $pattern);

        $spacePattern = '(<(?:(?!>.<).)*>|)'; // trash tags without content or empty
        $regexp =
            '/'
                .'( |)' // for save space if after replacement it will be on the end of tag without xml:space="preserve"
                .preg_quote($open)
                    .$spacePattern
                        .'%s'
                    .$spacePattern
                .preg_quote($close)
            .'/u'
        ;

        return $regexp;
    }

    /**
     * @param string $filePath
     */
    public function save($filePath = null)
    {
        $this->doc->save($filePath);
    }

    /**
     * @return string[]
     */
    public function getMarks()
    {
        $pattern = sprintf($this->replaceRegexp, '(?P<mark>' . self::MARK_NAME_REGEXP . ')');
        preg_match_all($pattern, $this->doc->getContent(), $matches);

        return array_unique($matches['mark']);
    }

    public function removeMarks()
    {
        foreach ($this->getMarks() as $mark) {
            $this->assign($mark, '');
        }
    }

    /**
     * @return bool
     */
    public function isFilled()
    {
        return ! (bool) $this->getMarks();
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
            $this->assignVar($key, $value);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function assignVar($key, $value)
    {
        $this->validateMarkName($key);

        $pattern = sprintf($this->replaceRegexp, preg_quote($key));
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');

        $replace = function ($matches) use ($value) {
            $space = $matches[1] == ' ' ? '&#160;' : ''; /** save space @see convertPattern */
            return $matches[2] . $space . $value . $matches[3];
        };

        $this->doc->setContent(
            preg_replace_callback($pattern, $replace, $this->doc->getContent())
        );
    }

    /**
     * @param string $mark
     * @throws Exception\Template\WrongMarkNameException
     */
    private function validateMarkName($mark)
    {
        if (!preg_match(sprintf('/^%s$/u', self::MARK_NAME_REGEXP), $mark)) {
            throw new Exception\Template\WrongMarkNameException($mark);
        }
    }
}