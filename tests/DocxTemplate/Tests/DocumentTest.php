<?php
namespace DocxTemplate\Tests;

use DocxTemplate\Document;

class DocumentTest extends BaseTestCase
{
    public function testOpen()
    {
        $doc = new Document($this->getFixturePath('test.docx'));
        $content = $doc->getContent();
        $this->assertContains('var1', $content);
    }

    public function testSave()
    {
        $doc = new Document($this->getFixturePath('test.docx'));
        $doc->setContent(file_get_contents($this->getFixturePath('simple_document.xml')));
        $doc->save($this->getRuntimePath('new.docx'));
        $this->assertFileExists($this->getRuntimePath('new.docx'));
    }
}
