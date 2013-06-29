<?php
namespace DocxTemplate\Exception\Template;

class WrongMarkNameException extends \Exception
{
    /**
     * @param string $mark
     */
    public function __construct($mark)
    {
        $message = sprintf("Wrong mark name '%s'. Name should be lowercase, with words separated by underscores.", $mark);
        parent::__construct($message);
    }
}