<?php
require "bootstrap.php";

use DocxTemplate\TemplateFactory;

$doc = new ExampleDoc("02-blocks.docx");
$template = TemplateFactory::load($doc->getOriginalPath());

$template->loop("block", [
    [
        'block_var' => "var1"
    ],

    [
        'block_var' => "var2"
    ],

    [
        'block_var' => "var3"
    ]
]);

$template->save($doc->getProcessedPath());
