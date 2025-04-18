<?php

namespace App\Lib;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

class AppSession
{
    public static function setSessionData($key, $value): void
    {
        $session = new Session();
        $session->set($key, $value);
    }

    public static function isAuthenticated($key): bool
    {
        try {
            $session = new Session();
            $user = "";
            if ($session->has($key)) {
                $user = $session->get($key);
            }
            if ($user) {
                return true;
            } else {
                return false;
            }

        } catch (\Exception $e) {
            AppSession::addError($e->getMessage());
        }

        return false;

    }

    public static function getSessionData($key)
    {
        $session = new Session();
        return $session->get($key);
    }

    public static function clearSessionData($key): void
    {
        $session = new Session();
        $session->remove($key);
    }

    public static function addError($message): void
    {
        $session = new Session();
        $curMessages = AppSession::getSessionData("error");
        if (!is_array($curMessages)) {
            $curMessages = array();
        }
        $curMessages[] = $message;
        AppSession::setSessionData("error", $curMessages);
    }

    public static function addSuccess($message): void
    {
        $session = new Session();
        $curMessages = AppSession::getSessionData("success");
        if (!is_array($curMessages)) {
            $curMessages = array();
        }
        $curMessages[] = $message;
        AppSession::setSessionData("success", $curMessages);
        $curMessages = AppSession::getSessionData("success");
    }

    public static function addHeader($message): void
    {
        $session = new Session();
        $curMessages = AppSession::getSessionData("header");
        if (!is_array($curMessages)) {
            $curMessages = array();
        }
        $curMessages[] = $message;
        AppSession::setSessionData("header", $curMessages);
    }

    public static function addInfo($message): void
    {
        $session = new Session();
        $curMessages = AppSession::getSessionData("info");
        if (!is_array($curMessages)) {
            $curMessages = array();
        }
        $curMessages[] = $message;
        AppSession::setSessionData("info", $curMessages);
    }

}
