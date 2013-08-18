<?php
namespace DocxTemplate;

class TemplateFactory
{
    /**
     * @param  string   $filePath
     * @param  string   $markPattern
     * @return Template
     */
    public static function load($filePath, $markPattern = Matcher::MARK_DEFAULT_PATTERN)
    {
        return new Template(
            new Document($filePath),
            new Matcher($markPattern) // todo cache
        );
    }
}
