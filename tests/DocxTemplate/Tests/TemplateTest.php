<?php
namespace DocxTemplate\Tests;

use DocxTemplate\Document;
use DocxTemplate\Template;

class TemplateTest extends BaseTestCase
{
    public function testTest()
    {
        $template = new Template(new Document($this->getFixturePath('data/test.docx')));
        $this->assertNotNull($template);
    }
}