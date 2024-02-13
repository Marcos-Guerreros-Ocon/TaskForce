<?php

class SessionManager
{


    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public function get($key)
    {
        return $_SESSION[$key];
    }
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function clear()
    {
        $_SESSION = array();
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function destroy()
    {
        session_destroy();
    }
}
