<?php

use \Bitrix\Main\EventManager;
use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(null, [
    'Lib\Utm' => '/local/php_interface/Lib/Utm.php',
    'Lib\Sale\Handlers' => '/local/php_interface/Lib/Sale/Handlers.php',
    'Lib\Xml\Import' => '/local/php_interface/Lib/Xml/Import.php',
    'Lib\Xml\Handlers' => '/local/php_interface/Lib/Xml/Handlers.php',
]);

$eventManager = EventManager::getInstance();
$eventManager->addEventHandler("sale", "OnSaleOrderSaved", [\Lib\Sale\Handlers::class, "saveUtm"]);

$eventManager->addEventHandler(
    'Morizo',
    'CustomMorizoTestTaskEvent',
    [\Lib\Xml\Handlers::class, "CustomMorizoTestTaskEvent"]
);