<?php

class Loger
{
    public static function log($text)
    {
        $file = '../App/Log/'.date('Ym').'.log';

        error_log(
            "[".date('Y-m-d H:i:s')."] ".$text."\n",
            3,
            $file
        );
    }
}
