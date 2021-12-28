<?php

namespace Lib;

use \Bitrix\Main\Application;
use \Bitrix\Main\Web\Cookie;

class Utm
{
    const UTM_SOURCE = "UTM_SOURCE";

    /**
     * Сохранение utm-меток в cookies
     * @return void
     */
    public static function saveUtmToCookies()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $utmSource = htmlspecialchars($request->getQuery("utm_source"));
        if ($utmSource) {
            $cookie = new Cookie(self::UTM_SOURCE, $utmSource);
            Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
        }
    }

    /**
     * Получение utm-меток из cookies
     * @param string $name
     * @return string
     */
    public static function getUtmFromCookies(string $name): string
    {
        $utm = Application::getInstance()->getContext()->getRequest()->getCookie($name);
        if ($utm) {
            return $utm;
        } else {
            return "";
        }
    }
}