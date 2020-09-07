<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 24-Jan-18
 * Time: 4:28 PM
 */

namespace Sotbit\Regions\Location;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Sotbit\Regions\Config\Option;
use Sotbit\Regions\Helper\LocationType;
use Sotbit\Regions\Internals\RegionsTable;

/**
 * Class EventHandlers
 * @package Sotbit\Regions\Location
 * @author Sergey Danilkin <s.danilkin@sotbit.ru>
 */
class EventHandlers
{

    /**
    Property name for city in: shop -- order properties -- list of properties
     */
    const city = 'CITY';

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
    public function OnSaleComponentOrderPropertiesHandler(
        &$arUserResult,
        $request,
        &$arParams,
        &$arResult
    )
    {
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        if(!$request->isAdminSection())
        {
            if(!Loader::includeModule('sotbit.regions'))
            {
                return true;
            }
            if(Option::get('INSERT_SALE_LOCATION',SITE_ID) != 'Y')
            {
                return true;
            }
            if(!Loader::includeModule('sale'))
            {
                return true;
            }

            $propCity = \Bitrix\Sale\Internals\OrderPropsTable::getList(
                [
                    'filter' => [
                        'PERSON_TYPE_ID' => $arUserResult['PERSON_TYPE_ID'],
                        'CODE' => self::city,
                    ],
                    'limit' => 1,
                    'cache'  => [
                        'ttl' => 36000000,
                    ],
                ]
            )->fetch();

            $saleLocation = new Sale($arUserResult['PERSON_TYPE_ID']);
            $propertyId = $saleLocation->getPropertyId();
            if(!$propertyId)
            {
                return true;
            }
            $Id = $saleLocation->getId();
            if(!$Id)
            {
                return true;
            }

            $city = \Bitrix\Sale\Location\LocationTable::getList([
                'filter' => [
                    'TYPE_ID' => LocationType::getCity(),
                    ( $_REQUEST['ORDER_PROP_6'] ? 'ID' : 'CODE' ) => ( isset($_REQUEST['ORDER_PROP_6']) ? intval($_REQUEST['ORDER_PROP_6']) : $Id )
                ],
                'order' => ['SORT' => 'ASC'],
                'cache' => ['ttl' => 36000000],
                'select' => ['*', 'NAME.*']
            ])->fetch();


            $arUserResult['ORDER_PROP'][$propertyId] = ( isset($city['CODE']) ? $city['CODE'] : $Id );
            if (isset($city['SALE_LOCATION_LOCATION_NAME_NAME'])) {
                $arUserResult['ORDER_PROP'][$propCity['ID']] = $_SESSION['SOTBIT_REGIONS']['REGION_FOR_BASCET'] = $city['SALE_LOCATION_LOCATION_NAME_NAME'];
            }

        }
    }

    /**
     * @param $content
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\SystemException
     */
    public function OnEndBufferContentHandler(&$content)
    {
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        if(
            !$request->isAdminSection() &&
//            !$request->isAjaxRequest() &&
            Loader::includeModule('sotbit.regions'))
        {
            $domain = new Domain();
            $content = $domain->getVariables()->replaceContent($content);
        }
    }

    /**
     * @param $event
     * @param $lid
     * @param $arFields
     * @param $message_id
     * @throws \Bitrix\Main\SystemException
     */
    public function OnBeforeEventAddHandler(&$event, &$lid, &$arFields, &$message_id)
    {
        if($event == 'STATISTIC_DAILY_REPORT')
        {
            return false;
        }
        $domain = new Domain();

        $codes = $domain->getVariables()->getCodes();
        unset($codes['MAP_YANDEX']);
        unset($codes['MAP_GOOGLE']);
        $variables = $domain->getVariables()->getValues($codes,$lid);

        if($variables)
        {
            foreach($variables as $key => $val)
            {
                $arFields[str_replace('#','',$key)] = $val;
            }
        }
    }

    /**
     * @param \Bitrix\Main\Event $mailParams
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\SystemException
     */
    public function OnBeforeMailSendHandler($mailParams)
    {
        $parameters = $mailParams->getParameters();
        $newParams = [];

        foreach($parameters as $i => $parameter){
            foreach($parameter as $key=>$value){
                if(is_string($value)){
                    if(strpos($value,'#SOTBIT_REGIONS') !== false){
                        $domain = new Domain();
                        $codes = $domain->getVariables()->getCodes();
                        unset($codes['MAP_YANDEX']);
                        unset($codes['MAP_GOOGLE']);
                        $variables = $domain->getVariables()->getValues($codes);
                        if($variables)
                        {
                            foreach($variables as $vkey => $vval)
                            {
                                $value = str_replace($vkey,$vval,$value);
                            }
                        }
                        $newParams[$key] = $value;
                    }
                }
                elseif(is_array($value)){
                    foreach($value as $k => $v){
                        if(strpos($v,'#SOTBIT_REGIONS') !== false){
                            $domain = new Domain();
                            $codes = $domain->getVariables()->getCodes();
                            unset($codes['MAP_YANDEX']);
                            unset($codes['MAP_GOOGLE']);
                            $variables = $domain->getVariables()->getValues($codes);
                            if($variables)
                            {
                                foreach($variables as $vkey => $vval)
                                {
                                    $v = str_replace($vkey,$vval,$v);
                                }
                                $value[$k] = $v;
                            }
                            $newParams[$key] = $value;
                        }
                    }
                }
            }
        }
        if($newParams){
            $result = new \Bitrix\Main\EventResult(1,$newParams);
            $mailParams->addResult($result);
        }
    }
}