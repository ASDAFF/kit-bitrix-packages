<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if(!\Bitrix\Main\Loader::includeModule('sotbit.regions')){
    return false;
}

use Bitrix\Main\Grid\Declension;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;

$arDays = array(
    Loc::getMessage('sotbit.regions_DELIVERY_DAY_1'),
    Loc::getMessage('sotbit.regions_DELIVERY_DAY_2'),
    Loc::getMessage('sotbit.regions_DELIVERY_DAY_3'),

);

$dayDeclension = new Declension(Loc::getMessage('sotbit.regions_DELIVERY_DAY_1'), Loc::getMessage('sotbit.regions_DELIVERY_DAY_2'), Loc::getMessage('sotbit.regions_DELIVERY_DAY_3'));

$objDateTime = new \Bitrix\Main\Type\DateTime;

if($arResult['DELIVERY'])
{
    foreach($arResult['DELIVERY'] as &$arDelivery)
    {
        if($arDelivery["PERIOD_TYPE"] == 'D')
        {
            $from = $arDelivery["PERIOD_FROM"];
            $to = $arDelivery["PERIOD_TO"];
            $from = !empty($from) ? $from : $to;
            $arDelivery["DAY"] = $from;
            $strTime = "";

            if($from)
            {
                $time = time() + \CTimeZone::GetOffset();
                if($from > 2)
                {
                    $strDay = $dayDeclension->get($from);
                    $strTime = Loc::getMessage('sotbit.regions_DELIVERY_AFTER_DAY') . ' ' . $from . ' ' . $strDay.', ';

                }elseif($from >= 0)
                {
                    $strDay = Loc::getMessage('sotbit.regions_DELIVERY_STR_DAY_'.$from).', ';
                    $strTime = $strDay;
                }

                $time = $time + $from * 3600 * 24;

                $strData = date('j', $time) . ' ' . FormatDate("F", $time);
                $strData = ToLower($strData);

                $arDelivery['TIME'] = $strTime.$strData;
            }

        }
    }
}