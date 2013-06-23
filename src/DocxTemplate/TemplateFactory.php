<?php
namespace DocxTemplate;

class TemplateFactory
{
    /**
     * @var string
     */
    private static $markPattern = Template::DEFAULT_MARK_PATTERN;

    /**
     * @param string $filePath
     * @return Template
     */
    public static function load($filePath)
    {
        return new Template(
            new Document($filePath),
            self::$markPattern
        );
    }

    /**
     * @param string $pattern
     */
    public static function setMarkPattern($pattern)
    {
        self::$markPattern = $pattern;
    }
}