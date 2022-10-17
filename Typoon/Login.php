<?php

class Login
{
    public static function set($id)
    {
        $_SESSION['LoginId'] = $id;

        //
        $_SESSION['uuId'] = strtoupper(hash(
            'sha512',
            hash('sha512', $id)."_".
            hash('sha512', "NameSpace")."_".
        //	hash('sha512', $_SERVER['REMOTE_ADDR'])."_".
            hash('sha512', microtime())."_".
            hash('sha512', rand())
        ));

        //
        $_SESSION['guId'] = substr(substr_replace($_SESSION['uuId'], 'A', 16, 1), 0, 32);
//      $_SESSION['guId'] = substr_replace($_SESSION['guId'], '4', 12, 1); // uuid V4
        $_SESSION['guId'] = preg_replace('#\A(.{8})(.{4})(.{4})(.{4})(.{12})#', '$1-$2-$3-$4-$5', $_SESSION['guId']);
    }

    public static function check()
    {
        if (isset($_SESSION['LoginId'])) {
            return;
        }

        header("Location: /logout");
        exit;
    }

    public static function logout()
    {
        header("Location: /logout");
        exit;
    }
}
