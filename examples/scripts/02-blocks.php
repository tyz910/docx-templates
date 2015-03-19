<?php
require "bootstrap.php";

use DocxTemplate\TemplateFactory;

$doc = new ExampleDoc("02-blocks.docx");
$template = TemplateFactory::load($doc->getOriginalPath());

$block = $template->extractBlock("block");
$template->assign([
    "block_placement" => $block
]);

$template->save($doc->getProcessedPath());
