<?php

class Login
{
    public static function set($id)
    {
        $_SESSION['LoginId'] = $id;
        $_SESSION['uuId'] = hash('sha512', 
									hash('sha512', $id)."_".
									hash('sha512', "NameSpace")."_".
								//	hash('sha512', $_SERVER['REMOTE_ADDR'])."_".
									hash('sha512', microtime())."_".
									hash('sha512', rand())
								);
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
        /*        // Delete Session.
                $_SESSION = array();
                if (isset($_COOKIE["PHPSESSID"])) {
                    setcookie("PHPSESSID", '', time() - 1800, '/');
                }
                session_destroy();

                // Go TopPage.
                $URI = "/";
        */
        header("Location: /logout");
        exit;
    }
}
