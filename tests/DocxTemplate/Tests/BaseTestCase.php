<?php
namespace DocxTemplate\Tests;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param  string $uri
     * @return string
     */
    protected function getFixturePath($uri)
    {
        return __DIR__ . '/../../fixtures/' . $uri;
    }

    /**
     * @param  string $uri
     * @return string
     */
    protected function getRuntimePath($uri)
    {
        return __DIR__ . '/../../runtime/' . $uri;
    }
}
