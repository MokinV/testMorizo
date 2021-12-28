<?php

namespace Lib\Sale;

use \Bitrix\Main;
use \Lib\Utm;

class Handlers
{
    const UTM_SOURCE = "UTM_SOURCE";

    /**
     * @param Main\Event $event
     * @return void
     * @throws Main\ArgumentException
     * @throws Main\ArgumentOutOfRangeException
     * @throws Main\NotImplementedException
     * @throws Main\ObjectPropertyException
     * @throws Main\SystemException
     */
    public static function saveUtm(Main\Event $event)
    {
        /** @var \Bitrix\Sale\Order $order */
        $order = $event->getParameter("ENTITY");
        $isNew = $event->getParameter("IS_NEW");

        if ($isNew) {
            $utmSource = Utm::getUtmFromCookies(Utm::UTM_SOURCE);
            if ($utmSource !== "") {
                $propertyCollection = $order->getPropertyCollection();
                $utmPropValue = $propertyCollection->getItemByOrderPropertyCode(self::UTM_SOURCE);
                if ($utmPropValue) {
                    $utmPropValue->setValue($utmSource);
                    $utmPropValue->save();
                }
            }
        }
    }
}
