<?php
namespace DocxTemplate\Tests;

use DocxTemplate\Document;
use DocxTemplate\Matcher;
use DocxTemplate\Template;

class TemplateTest extends BaseTestCase
{
    /**
     * @var Template
     */
    private $template;

    protected function setUp()
    {
        $this->template = new Template(
            new Document($this->getFixturePath('test.docx')),
            new Matcher()
        );
        parent::setUp();
    }

    /**
     * @return string
     */
    private function saveAndGetContent()
    {
        $path = $this->getRuntimePath('doc.docx');
        $this->template->save($path);
        $doc = new Document($path);

        return $doc->getContent();
    }

    public function testAssign()
    {
        $this->template->assign('var1', 'val1');
        $content = $this->saveAndGetContent();
        $this->assertContains('val1', $content);
    }

    public function testMultipleAssign()
    {
        $this->template->assign([
            'var1' => 'val1',
            'var2' => 'val2'
        ]);
        $content = $this->saveAndGetContent();
        $this->assertContains('val1', $content);
        $this->assertContains('val2', $content);
    }

    public function testAssignSpecSymbols()
    {
        $this->template->assign('var1', '<special_symbols>');
        $content = $this->saveAndGetContent();
        $this->assertContains('&lt;special_symbols&gt;', $content);
    }

    public function testAssignInline()
    {
        $this->template->assign('var4', 'text');
        $content = $this->saveAndGetContent();
        $this->assertContains('inlinetext', $content);
    }

    public function testAssignUtf8()
    {
        $this->template->assign('var3', 'русский текст');
        $content = $this->saveAndGetContent();
        $this->assertContains('русский текст', $content);
    }
}
