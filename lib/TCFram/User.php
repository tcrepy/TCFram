<?php

namespace TCFram;

session_start();

class User extends ApplicationComponent
{
    public function getAttribute($attr)
    {
        return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
    }

    public function getFlash()
    {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    public function setAuthenticated($authenticated = true)
    {
        if (!is_bool($authenticated)) {
            throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean');
        }

        $_SESSION['auth'] = $authenticated;
    }

    public function setFlash($value)
    {
        $_SESSION['flash'] = $value;
    }

    public function setSessionDatas($tab)
    {
        if (!empty($_SESSION)) {
            $_SESSION = array_merge($_SESSION, $tab);
        } else {
            $_SESSION = $tab;
        }
    }

    /**
     * @param $slug
     * @return mixed
     * @throws \Exception
     */
    public function getSessionDatas($slug)
    {
        if (isset($_SESSION[$slug])) {
            return $_SESSION[$slug];
        } else {
            throw new \Exception('La clé demandée n\'existe pas');
        }
    }

    public function unsetSessionDatas(Array $tab)
    {
        foreach ($tab as $key) {
            unset($_SESSION[$key]);
        }
    }
}