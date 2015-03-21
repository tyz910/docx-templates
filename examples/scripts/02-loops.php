<?php
require "bootstrap.php";

use DocxTemplate\TemplateFactory;

$doc = new ExampleDoc("02-loops.docx");
$template = TemplateFactory::load($doc->getOriginalPath());

$template->loop("loop", [
    [
        'var' => "iteration1"
    ],

    [
        'var' => "iteration2"
    ],

    [
        'var' => "iteration3"
    ]
]);

$template->save($doc->getProcessedPath());
