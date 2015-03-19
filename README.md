docX-templates
==============

[![Latest Stable Version](https://poser.pugx.org/tyz910/docx-templates/v/stable.png)](https://packagist.org/packages/tyz910/docx-templates)
[![Build Status](https://travis-ci.org/tyz910/docx-templates.png?branch=master)](https://travis-ci.org/tyz910/docx-templates)
[![Coverage Status](https://coveralls.io/repos/tyz910/docx-templates/badge.png?branch=master)](https://coveralls.io/r/tyz910/docx-templates?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/tyz910/docx-templates/badges/quality-score.png?s=94698728d5ab82944d818b003cdafb8122c4e627)](https://scrutinizer-ci.com/g/tyz910/docx-templates/)

You can create a .docx template file with included search-patterns that can be replaced by any value you wish:

```php
<?php
use DocxTemplate\TemplateFactory;

$template = TemplateFactory::load('template1.docx');
$template->assign([
    'name'    => 'Jon',
    'surname' => 'Snow',
    'house'   => 'Stark'
]);
$template->save('jon.docx');
```

Search-pattern spelling
-----------------------

Default search-pattern spelling is: **{{mark_name}}**  
Mark name should be lowercase, with words separated by underscores.

    My name is {{name}}.
    
Also you can use any search-pattern spelling you wish:

```php
<?php
use DocxTemplate\TemplateFactory;

$template = TemplateFactory::load('template.docx', '${%s}'); // %s will be replaced with mark name
```

## Installation using [Composer](http://getcomposer.org/)

Add to your `composer.json`:

```json
{
    "require" :  {
        "tyz910/docx-templates": "*"
    }
}
```
