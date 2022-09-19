<?php

// https://github.com/divengine/div
// https://divengine.org/docs/div-php-template-engine
require_once("../Lib/div.php");
use divengine\div;

class Html
{
    public static function echo($file, $data)
    {
        global $EXEC;
        echo new div("../App/Action/{$EXEC}/".$file, $data);
    }
}
