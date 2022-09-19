<?php

class Login
{
    public static function set($id)
    {
        $_SESSION['LoginId'] = $id;
        $_SESSION['LoginToken'] = hash('ripemd160', microtime());
    }

    public static function check()
    {
        if (isset($_SESSION['LoginId'])) {
            return;
        }

        header("Location: /logout");
        exit;
    }
}
