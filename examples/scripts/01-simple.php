<?php
require "bootstrap.php";

use DocxTemplate\TemplateFactory;

$doc = new ExampleDoc("01-simple.docx");
$template = TemplateFactory::load($doc->getOriginalPath());
$template->assign([
    "hello" => "Hello world!",
    "utf8"  => "Привет мир!",
    "special" => '<>!@#$%^&*()_+;:"\'\\|/'
]);
$template->save($doc->getProcessedPath());
