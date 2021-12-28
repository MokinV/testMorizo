<?php

namespace Lib\Xml;

class Handlers
{
    /**
     * @param \Bitrix\Main\Event $event
     * @return void
     */
    public static function CustomMorizoTestTaskEvent(\Bitrix\Main\Event $event)
    {
        $text = $event->getParameter('text');
        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/logs/log.txt");
        AddMessage2Log($text, __class__);
    }
}
