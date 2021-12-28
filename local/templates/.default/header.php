<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
global $USER, $APPLICATION;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Сохранение utm-меток в cookies
 */
\Lib\Utm::saveUtmToCookies();

// Html-код
?>
....
