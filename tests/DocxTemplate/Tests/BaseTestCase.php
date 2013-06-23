<?php
namespace DocxTemplate\Tests;

use DocxTemplate\Document;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $uri
     * @return string
     */
    protected function getFixturePath($uri)
    {
        return __DIR__ . '/../../fixtures/' . $uri;
    }
}