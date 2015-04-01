<?php
namespace DocxTemplate\Tests;

use DocxTemplate\TemplateFactory;

class TemplateFactoryTest extends BaseTestCase
{
    public function testLoad()
    {
        $template = TemplateFactory::load($this->getFixturePath('test.docx'));
        $this->assertInstanceOf('DocxTemplate\Template', $template);
    }

    public function testUseMarkSyntax()
    {
        TemplateFactory::useMarkSyntax('[', ']');
        $template = TemplateFactory::load($this->getFixturePath('mark_syntax_test.docx'));
        $marks = $template->getDocContent()->getMarks();
        $this->assertContains('var1', $marks);
        $this->assertContains('var2', $marks);
    }
}
