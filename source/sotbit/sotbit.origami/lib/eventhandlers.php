<?php

namespace Sotbit\Origami;

use Bitrix\Main;
use Bitrix\Main\Loader;
use Sotbit\Origami\Helper\Menu;
use Bitrix\Main\Application;
use Sotbit\Origami\Helper\Config;
use Sotbit\Origami\Config\Option;
use Sotbit\Origami\Sale\Basket;

class EventHandlers
{
    static $itemCount = 0;

    /**
     * @param $arGlobalMenu
     * @param $arModuleMenu
     */
    public function OnBuildGlobalMenuHandler(&$arGlobalMenu, &$arModuleMenu)
    {
        Menu::getAdminMenu($arGlobalMenu, $arModuleMenu);
    }

    /**
     * @param $content
     */
    public function OnEndBufferContentHandler(&$content)
    {
        $content = str_replace(" type=\"text/javascript\"", false, $content);

        \SotbitOrigami::DoInlineCss($content);
    }

    /**
     * @param $arUserResult
     * @param $request
     * @param $arParams
     * @param $arResult
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\SystemException
     */
    public function OnSaleComponentOrderPropertiesHandler(&$arUserResult, $request, &$arParams, &$arResult)
    {
        if(!$request->isAdminSection())
        {
            if(\SotbitOrigami::isUseRegions() && $_SESSION['SOTBIT_REGIONS']['LOCATION']['CODE'] && Config::get('INSERT_LOCATION_IN_ORDER') == 'Y')
            {
                $prop = \Bitrix\Sale\Internals\OrderPropsTable::getList(
                    [
                        'filter' => [
                            'PERSON_TYPE_ID' => $arUserResult['PERSON_TYPE_ID'],
                            'IS_LOCATION' => 'Y'
                        ],
                        'limit' => 1,
                        'cache'  => [
                            'ttl' => 36000000,
                        ],
                    ]
                )->fetch();
                if($prop['ID'] > 0)
                {
                    $arUserResult['ORDER_PROP'][$prop['ID']] = $_SESSION['SOTBIT_REGIONS']['LOCATION']['CODE'];
                }
            }
        }
    }

    /**
     * for change values before public component save
     */
    public function OnBeforePrologHandler()
    {
        $server = \Bitrix\Main\Context::getCurrent()->getServer();

        //if($server->get('SCRIPT_URL') == '/bitrix/admin/component_props.php')
        if(isset($_REQUEST['component_name']) && isset($_REQUEST['component_template']) && isset($_REQUEST['template_id']))
        {
            $iblockId = $_POST['IBLOCK_ID'];
            $iblockType = $_POST['IBLOCK_TYPE'];
            if(strpos($iblockId,'Config::get') !== false && strpos($iblockType,'Config::get') === false)
            {
                preg_match_all('#("|\')(.*)("|\')#i', $iblockId, $matches);
                $code = $matches[2][0];
                if($code)
                {
                    $code = str_replace('IBLOCK_ID','IBLOCK_TYPE',$code);
                    $_POST['IBLOCK_TYPE'] = '={Config::get("'.$code.'")}';
                }
            }

            if(isset($_POST['PRICE_CODE']) && isset($_POST["COMPONENT_TEMPLATE"]) && strpos($_POST["COMPONENT_TEMPLATE"], 'origami_') !== false)
            {

                if(!$_POST['PRICE_CODE'] || (count($_POST['PRICE_CODE']) == 1 && $_POST['PRICE_CODE'][0] === ''))
                {
                    Loader::includeModule('catalog');
                    $_POST['PRICE_CODE'] = [];
                    $rs = \Bitrix\Catalog\GroupTable::getList(['select' => ['NAME']]);

                    while($price = $rs->fetch())
                    {
                        $_POST['PRICE_CODE'][] = $price['NAME'];
                    }
                }
                foreach($_POST['PRICE_CODE'] as &$priceCode)
                {
                    $priceCode = '"'.$priceCode.'"';
                }
                $_POST['PRICE_CODE'] = '={\SotbitOrigami::GetComponentPrices(['.implode(',',$_POST['PRICE_CODE']).'])}';
            }
        }
    }

    function onChangeOfferNameBasket(\Bitrix\Main\Event $event)
    {
        \Bitrix\Main\Loader::includeModule('sotbit.origami');

        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        $name = $event->getParameter('NAME');
        $value = $event->getParameter('VALUE');
        $entity = $event->getParameter('ENTITY');
        $oldValue = $event->getParameter('OLD_VALUE');

        $site_id = $request->get('site_id');
        $template = $request->get('template');


        if($name == "NAME" && $site_id && strpos($template, "origami_") !== false)
        {
            $offerText = trim(Option::get('OFFER_NAME', $site_id));

            if($offerText)
            {
                $ID = $entity->getId();
                $collection = $entity->getCollection();

                foreach ($collection as $basketItem)
                {
                    $item = $basketItem->findItemById($ID);

                    if($item && $value)
                    {
                        $fields = $item->getFields();
                        $arValues = $fields->getValues();
                        $productID = $arValues["PRODUCT_ID"];

                        if($productID)
                        {
                            $arProp = $item->getPropertyCollection()->getPropertyValues();

                            foreach($arProp as $code => $arPropVal)
                            {
                                if($code != "CATALOG.XML_ID" && $code != "PRODUCT.XML_ID")
                                {
                                    $props[] = $code;
                                }
                            }

                            $IBLOCK_ID = Config::get("IBLOCK_ID", $site_id);
                            $arCatalog = \CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);

                            $SKU_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
                            $SKU_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];

                            $arSkuFilter = array(
                                "IBLOCK_ID" => $SKU_IBLOCK_ID,
                                "ID" => $productID,
                            );

                            if($arCatalog)
                            {
                                $rsElements = \CIBlockElement::GetList(array(), $arSkuFilter, false, Array("nTopCount" => 1), array("ID", "NAME", "PROPERTY_".$SKU_PROPERTY_ID, "PROPERTY_".$SKU_PROPERTY_ID.".NAME"));

                                while($arSku = $rsElements->Fetch())
                                {
                                    $parentID = $arSku["PROPERTY_".$SKU_PROPERTY_ID."_VALUE"];
                                    $productName = $arSku["NAME"];
                                    $parentName = $arSku["PROPERTY_".$SKU_PROPERTY_ID."_NAME"];

                                    if($parentID)
                                    {

                                        $tmpResult = [
                                            'ID' => $parentID,
                                            'NAME' => $parentName,
                                            'OFFERS' => [
                                                0 => [
                                                    'ID' => $productID,
                                                    'NAME' => $productName,
                                                ]
                                            ]
                                        ];

                                        $Offer = new \Sotbit\Origami\Helper\Offer($site_id);
                                        $newName = $Offer->changeText($tmpResult, $props);

                                        $event->addResult(
                                            new Main\EventResult(
                                                Main\EventResult::SUCCESS, array('VALUE' => $newName)
                                            )
                                        );
                                    }
                                }
                            }
                        }
                    }

                }
            }

        }
    }

    function changeOfferNameBasket(\Bitrix\Main\Event $event)
    {
        \Bitrix\Main\Loader::includeModule('sotbit.origami');

        return;

        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        $basket = $request->get('basket');
        $template = $request->get('template');
        $site_id = $request->get('site_id');

        if(!$site_id)
            $site_id = SITE_ID;

        $offerText = trim(Option::get('OFFER_NAME', $site_id));

        $arValues = $event->getParameter("VALUES");

        if($offerText)
        {

            if(!$arValues && $basket && strpos($template, "origami_") !== false)
                $arItems = $event->getParameter("ENTITY");
            /*elseif($arValues && !$basket)
            {
                EventHandlers::$itemCount++;
                $arItems = $event->getParameter("ENTITY")->getCollection();
                if(EventHandlers::$itemCount < count($arItems))
                    return true;
            }*/

            if($arItems)
            {
                $productID = 0;
                $IBLOCK_ID = Config::get("IBLOCK_ID", $site_id);
                $arCatalog = \CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);

                $SKU_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
                $SKU_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];

                foreach ($arItems as $basketItem)
                {
                    $props = array();
                    {
                        $ID = $basketItem->getField('ID');
                        $productID = $basketItem->getField('PRODUCT_ID');
                        $arProp = $basketItem->getPropertyCollection()->getPropertyValues();

                        foreach($arProp as $code => $arPropVal)
                        {
                            if($code != "CATALOG.XML_ID" && $code != "PRODUCT.XML_ID")
                            {
                                $props[] = $code;
                            }
                        }

                        if($productID)
                        {
                            $arSkuFilter = array(
                                "IBLOCK_ID" => $SKU_IBLOCK_ID,
                                "ID" => $productID,
                            );

                            $rsElements = \CIBlockElement::GetList(array(), $arSkuFilter, false, Array("nTopCount" => 1), array("ID", "NAME", "PROPERTY_".$SKU_PROPERTY_ID, "PROPERTY_".$SKU_PROPERTY_ID.".NAME"));

                            while($arSku = $rsElements->Fetch())
                            {
                                $parentID = $arSku["PROPERTY_".$SKU_PROPERTY_ID."_VALUE"];
                                $productName = $arSku["NAME"];
                                $parentName = $arSku["PROPERTY_".$SKU_PROPERTY_ID."_NAME"];

                                if($parentID)
                                {

                                    $tmpResult = [
                                        'ID' => $parentID,
                                        'NAME' => $parentName,
                                        'OFFERS' => [
                                            0 => [
                                                'ID' => $productID,
                                                'NAME' => $productName,
                                            ]
                                        ]
                                    ];

                                    $Offer = new \Sotbit\Origami\Helper\Offer($site_id);
                                    $newName = $Offer->changeText($tmpResult, $props);
                                    $basketItem->setField('NAME', $newName);
                                    //printr(array("ID" => $ID));

                                    //$resultBasket = \Bitrix\Sale\Internals\BasketTable::Update($ID, array("NAME" => $newName));

                                }
                            }
                        }
                    }

                    unset($props);
                }
            }
        }
    }
}