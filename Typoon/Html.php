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
        exit;
    }

    public static function file($outputfile, $file, $data)
    {
        if (! file_exists($outputfile)) {
            return false;
        }

        file_put_contents($outputfile, new div($file, $data), LOCK_EX);
        return true;
    }

    public static function echoJson($data)
    {
        header("Content-type: application/json");
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function echoFile($file)
    {
        if (! file_exists($file)) {
            return false;
        }

        preg_match("#\.(.+)\z#", $file, $ext);
        $ext = DataFile::mimeAudio($ext[1]);

        $mime = "";

        switch($ext) {
            case 'ogg':
                $mime = "audio/ogg";
                break;
            case 'mp3':
                $mime = "audio/mpeg";
                break;
            case 'm4a':
                $mime = "audio/aac";
                break;
            default:
            break;
        }

        if ($mime !== "") {
            header("Content-type: {$mime}");
        }

        echo(file_get_contents($file));

        return true;
    }

    /*

    */

    public static function mimeAudio($ext)
    {
        $ext = trim($ext, "\.");

        $mime = "";

        switch($ext) {
            case 'ogg':
                $mime = "audio/ogg";
                break;
            case 'mp3':
                $mime = "audio/mpeg";
                break;
            case 'm4a':
                $mime = "audio/aac";
                break;
            default:
            break;
        }

        return $mime;
    }
}
