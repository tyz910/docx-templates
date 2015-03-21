<?php
namespace DocxTemplate;

use DocxTemplate\Matcher\MatcherInterface;
use DocxTemplate\Matcher\RegExpMatcher;

class TemplateFactory
{
    /**
     * @var MatcherInterface
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
        self::$defaultMatcher = new RegExpMatcher($open, $close, $strictMatch, $markNameRegExp);
    }

    /**
     * @return MatcherInterface
     */
    protected static function getDefaultMatcher()
    {
        if (!self::$defaultMatcher) {
            self::$defaultMatcher = new RegExpMatcher();
        }

        return self::$defaultMatcher;
    }

    /**
     * @param  string            $filePath
     * @param  MatcherInterface  $matcher
     * @return Template
     */
    public static function load($filePath, MatcherInterface $matcher = null)
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
