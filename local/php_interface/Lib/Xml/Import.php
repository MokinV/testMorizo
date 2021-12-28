<?php

namespace Lib\Xml;

use \Bitrix\Main;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\IblockTable;

class Import
{
    const IBLOCK_CODE = 'MORIZO_PRODUCTS';

    protected int $countSuccessAdd = 0;
    protected int $countSuccessUpdate = 0;
    protected array $arProducts = [];
    protected string $fileName = '';
    protected int $iBlockId = 0;

    public function __construct()
    {
        //$this->fileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/import/products_3.xml';
        $this->fileName = $_SERVER['DOCUMENT_ROOT'] . '/upload/import/products_20000.xml';
        $this->iBlockId = self::checkIBlock();
        if (!$this->iBlockId) {
            self::addIBlock();
        }
    }

    /**
     * @return void
     */
    protected function addIBlock()
    {
        $obIBlock = new \CIBlock;
        $arFields = [
            "NAME" => self::IBLOCK_CODE,
            "CODE" => self::IBLOCK_CODE,
            "IBLOCK_TYPE_ID" => 'news',
            "SITE_ID" => "s1",
        ];
        $id = $obIBlock->Add($arFields);
        if (intval($id)) {
            $this->iBlockId = $id;
        }
    }

    /**
     * @return int
     * @throws Main\ArgumentException
     * @throws Main\ObjectPropertyException
     * @throws Main\SystemException
     */
    protected function checkIBlock(): int
    {
        $dbItems = IblockTable::getList(
            [
                "filter" => [
                    "CODE" => self::IBLOCK_CODE
                ],
                "select" => [
                    "ID"
                ]
            ]
        );
        return intval($dbItems->fetch()["ID"]);
    }

    protected function parser()
    {
        if (file_exists($this->fileName)) {
            $xml = new \XMLReader();
            $xml->open($this->fileName);
            while ($xml->read()) {
                while ($xml->name === 'product') {
                    $node = new \SimpleXMLElement($xml->readOuterXML());
                    $this->arProducts[] = [
                        "name" => $node->name[0]->__toString(),
                        "description" => $node->description[0]->__toString(),
                    ];
                    unset($node);
                    $xml->next('product');
                }
            }
            $xml->close();
            unset($xml);
        }
    }

    /**
     * @return void
     * @throws Main\ArgumentException
     * @throws Main\ObjectPropertyException
     * @throws Main\SystemException
     */
    protected function checkProducts()
    {
        if (count($this->arProducts)) {
            foreach ($this->arProducts as $arProduct) {
                $arProduct["ID"] = self::checkProduct($arProduct["name"]);
                if ($arProduct["ID"]) {
                    self::update($arProduct);
                } else {
                    self::add($arProduct);
                }
            }
        }
    }

    /**
     * @param string $name
     * @return int
     * @throws Main\ArgumentException
     * @throws Main\ObjectPropertyException
     * @throws Main\SystemException
     */
    protected function checkProduct(string $name): int
    {
        $dbItems = ElementTable::getList(
            [
                'select' => [
                    'ID'
                ],
                'filter' => [
                    'IBLOCK_ID' => $this->iBlockId,
                    "NAME" => $name
                ],
                'limit' => 1
            ]
        );
        return intval($dbItems->fetch()["ID"]);
    }

    /**
     * Создание товара
     * @param array $arProduct
     * @return void
     */
    protected function add(array $arProduct)
    {
        $el = new \CIBlockElement;

        $arLoadProductArray = [
            "IBLOCK_ID" => $this->iBlockId,
            "NAME" => $arProduct["name"],
            "DETAIL_TEXT" => $arProduct["description"],
        ];

        if ($el->Add($arLoadProductArray)) {
            $this->countSuccessAdd++;
        }
    }

    /**
     * Обновление товара
     * @param array $arProduct
     * @return void
     */
    protected function update(array $arProduct)
    {
        $el = new \CIBlockElement;

        $arLoadProductArray = [
            "DETAIL_TEXT" => $arProduct["description"],
        ];

        if ($el->update($arProduct["ID"], $arLoadProductArray)) {
            $this->countSuccessUpdate++;
        }
    }

    /**
     * @return void
     * @throws Main\ArgumentException
     * @throws Main\ObjectPropertyException
     * @throws Main\SystemException
     */
    public function load()
    {
        if ($this->iBlockId) {
            $this->parser();
            $this->checkProducts();

            $text = "Количество успешно записанных в ИБ сущностей:" . $this->countSuccessAdd;
        } else {
            $text = "Ошибка создания инфоблока";
        }
        $event = new \Bitrix\Main\Event(
            "Morizo",
            "CustomMorizoTestTaskEvent",
            ["text" => $text]
        );
        $event->send();
    }

}
