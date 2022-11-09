<?php

class Form
{
    /*

    */

    public static function mode()
    {
        if (isset($_POST['mode'])) {
            return trim($_POST['mode']);
        }
        return "";
    }

    public static function ipToken()
    {
        $ip = "";
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return hash(
            'ripemd160',
            $ip.$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_ACCEPT_LANGUAGE']
        );
        // ブラウザーの情報等を保存せずにハッシュ化
        // スマホなどはipアドレスが変化する可能性があるので、フォーム作成時ハッシュ作成
    }

    /*

    */

    public static function token()
    {
        $_SESSION['TyIpToken'] = self::ipToken();

        $_SESSION['TyFormToken'] = hash('ripemd160', microtime());
        return $_SESSION['TyFormToken'];
    }

    public static function tokenCheck($var)
    {
        //
        if (empty($_SESSION['TyIpToken'])) {
            return false;
        }

        $ipcheck = $_SESSION['TyIpToken'];
        unset($_SESSION['TyIpToken']);

        //
        if (empty($_SESSION['TyFormToken'])) {
            return false;
        }

        $check = $_SESSION['TyFormToken'];
        unset($_SESSION['TyFormToken']);

        if ($check === $var && $ipcheck === self::ipToken()) {
            return true;
        }
        return false;
    }
}
