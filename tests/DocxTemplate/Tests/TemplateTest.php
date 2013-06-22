<?php
namespace DocxTemplate\Tests;

use DocxTemplate\Template;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    public function testTest()
    {
        $template = new Template();
        $this->assertNotNull($template);
    }
}