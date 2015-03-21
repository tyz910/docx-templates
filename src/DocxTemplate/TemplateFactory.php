<?php
namespace DocxTemplate;

class TemplateFactory
{
    /**
     * @var Matcher
     */
    private static $defaultMatcher;

    /**
     * @param string $open
     * @param string $close
     * @param bool   $strictMatch
     * @param string $markNameRegExp
     */
    public static function useMarkSyntax($open, $close, $strictMatch = false, $markNameRegExp = null)
    {
        self::$defaultMatcher = new Matcher($open, $close, $strictMatch, $markNameRegExp);
    }

    /**
     * @return Matcher
     */
    protected static function getDefaultMatcher()
    {
        if (!self::$defaultMatcher) {
            self::$defaultMatcher = new Matcher();
        }

        return self::$defaultMatcher;
    }

    /**
     * @param  string   $filePath
     * @param  Matcher  $matcher
     * @return Template
     */
    public static function load($filePath, Matcher $matcher = null)
    {
        if (!$matcher) {
            $matcher = self::getDefaultMatcher();
        }

        return new Template(
            new Document($filePath),
            $matcher
        );
    }
}
