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

    public function testGetMarks()
    {
        $this->assertEquals(['var1', 'var2', 'var3', 'var4'], $this->template->getMarks());

        $this->template->assign([
            'var1' => 'val1',
            'var2' => 'val2'
        ]);
        $this->assertEquals(['var3', 'var4'], $this->template->getMarks());

        $this->template->assign([
            'var3' => 'val3',
            'var4' => 'val4'
        ]);
        $this->assertEquals([], $this->template->getMarks());
    }

    public function testIsFilled()
    {
        $this->assertFalse($this->template->isFilled());

        $this->template->assign([
            'var1' => 'val1',
            'var2' => 'val2',
            'var3' => 'val3',
            'var4' => 'val4'
        ]);

        $this->assertTrue($this->template->isFilled());
    }

    public function testRemoveMarks()
    {
        $this->template->removeMarks();
        $content = $this->saveAndGetContent();

        $this->assertNotContains('var1', $content);
        $this->assertNotContains('var2', $content);
        $this->assertNotContains('var3', $content);
        $this->assertNotContains('var4', $content);
    }
}
