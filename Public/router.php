<?php

    /*

    */

    // Null check.

    function sanitizer($arr)
    {
        if (is_array($arr)) {
            return array_map('sanitizer', $arr);
        }
        if (strpos($arr, "\0")) {
            errorPage(503);
            exit;
        }
        return $arr;
    }

    // URL Router.

    $GET = array();

    $EXEC = "";

    $URI = "";

    function urlFunc($url, $func)
    {
        global $GET, $EXEC, $URI;

        // Use preg "*".
        $url = preg_replace("#\/\*(.+?)(\/|\z)#", "\/(?P<$1>.*?)$1$2", $url);

        // Use preg ":".
        $url = preg_replace("#\/\:(.+?)(\/|\z)#", "\/(?P<$1>.*?)$2", $url);

        // Clean get.
        if (preg_match("#\A{$url}\z#", $URI, $arr)) {
            foreach ($arr as $key_ => $value_) {
                // URL decode & Trim.
                $arr[$key_] = preg_replace('#\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z#u', '', urldecode($arr[$key_]));
            }

            // Delete uri data.
            array_shift($arr);

            //
            $GET = $arr;
            $GET = sanitizer($GET);

            //$exec = $value;
            if (! file_exists("../App/Action/{$func}/index.php")) {
                errorPage(404);
                exit;
            }

            $EXEC = $func;
            require_once("../App/Action/{$func}/index.php");

            exit;
        }
    }

    function errorPage($code)
    {
        if ($code !== 503) {
            $code = 404;
        }

        header("Location: /{$code}.html");
        exit;
    }

    /*

    */

    // Setting.

    require_once("../Typoon/Router/setting.php");

    // if (!defined('systemName')) exit;

    define("systemName", "TypoonV4");

    // HTTPS?

    $_SERVER = sanitizer($_SERVER);

    if ($TyHttps === true && empty($_SERVER['HTTPS'])) {
        exit;
    }

    // Session Seting.

    ini_set('session.cookie_httponly', 1); // http only.

    ini_set('session.use_strict_mode', 1); // server mode only.

    if (isset($_SERVER['HTTPS'])) {
        ini_set('session.cookie_secure', 1);
    } // if https then.

    session_name($TySessionName);

    session_start();

    session_regenerate_id();

    // Error message?

    if ($TyDebug === true) {
        ini_set('display_errors', 'On');
        ini_set('display_startup_errors', 'On');
        ini_set('error_reporting', -1);
        ini_set('log_errors', 'On');
    } else {
        ini_set('display_errors', 'Off');
        ini_set('display_startup_errors', 'Off');
        ini_set('error_reporting', E_ALL);
        ini_set('log_errors', 'On');
    }

    // Domain????????????

    if ($_SERVER['HTTP_HOST'] !== $TyDomainName) {
        errorPage(404);
        exit;
    }

    // ??????????????????????????????????????????

    $URI = $_SERVER["REQUEST_URI"];

    if ($URI === "/Router.php") {
        errorPage(404);
        exit;
    }

    if ($URI === "/favicon.ico") {
        return false;
    }

    if (file_exists(".".$_SERVER["REQUEST_URI"]) && $_SERVER["REQUEST_URI"] !== "/") {
        return false;
    }

    // URL?????????????????????

    if (! preg_match("#\A[A-Za-z0-9\-\.\_\~\/\%]+\z#", $_SERVER["REQUEST_URI"])) {
        errorPage(404);
        exit;
    }

    // URL?????????????????????

    if (strlen($_SERVER["REQUEST_URI"]) > 1000) {
        errorPage(404);
        exit;
    }

    // Life????????????

    if ($_SERVER["REQUEST_URI"] === "/life") {
        echo("YES.");
        exit;
    }

    // logout.

    if ($_SERVER["REQUEST_URI"] === "/logout") {
        // Delete Session.
        $_SESSION = array();
        if (isset($_COOKIE["PHPSESSID"])) {
            setcookie("PHPSESSID", '', time() - 1800, '/');
        }
        session_destroy();

        // Go TopPage.
//        header("Location: /logout.html");
        header("Location: /");
        exit;
    }

    // Null?????????????????????

    $_GET = array();

    $_POST = sanitizer($_POST);

    $_COOKIE = sanitizer($_COOKIE);

    // md Setting.

    mb_language("Japanese");

    mb_internal_encoding("UTF-8");

    // Timezone24.

    date_default_timezone_set('Asia/Tokyo');

    // require.

    require_once("../Typoon/Verify.php");

    require_once("../Typoon/Form.php");

    require_once("../Typoon/Html.php");

    require_once("../Typoon/Login.php");

    // EXEC.

    require_once("../Typoon/Router/exec.php");

    // Not EXEC then Error page.

    errorPage(404);

    exit;
