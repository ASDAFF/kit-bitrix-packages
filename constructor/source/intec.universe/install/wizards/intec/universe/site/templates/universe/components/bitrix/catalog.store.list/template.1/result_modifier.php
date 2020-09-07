<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;


$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'LINE_COUNT' => 4,
    'SUB_SECTIONS_SHOW' => 'N',
    'SUB_SECTIONS_MAX' => 3
], $arParams);

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ]
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$sMapId = $arParams['MAP_ID'];
$iMapIdLength = StringHelper::length($sMapId);
$oMapIdExpression = new RegExp('^[A-Za-z_][A-Za-z01-9_]*$');

if ($iMapIdLength <= 0 || $oMapIdExpression->isMatch($sMapId))
    $arParams['MAP_ID'] = 'MAP_'.RandString();

unset($sMapId);
unset($iMapIdLength);
unset($oMapIdExpression);

$arResult['MAP'] = [
    'SHOW' => false,
    'VENDOR' => $arResult['MAP']
];

unset($arResult['VIEW_MAP']);

$arResult['POSITION'] = null;
$arResult['PLACEMARKS'] = [];

$arStores = Arrays::fromDBResult(CCatalogStore::GetList([], [
    'ACTIVE' => 'Y'
]))->indexBy('ID');

if (empty($arResult['STORES']))
    $arResult['STORES'] = [];

foreach ($arResult['STORES'] as &$arStore) {
    $arStore['EMAIL'] = null;
    $rsStore = $arStores->get($arStore['ID']);

    if (!empty($rsStore))
        $arStore['EMAIL'] = $rsStore['EMAIL'];

    if (!empty($arStore['PHONE'])) {
        $arStore['PHONE'] = [
            'VALUE' => StringHelper::replace($arStore['PHONE'], [
                '(' => '',
                ')' => '',
                ' ' => '',
                '-' => ''
            ]),
            'DISPLAY' => $arStore['PHONE']
        ];
    } else {
        $arStore['PHONE'] = null;
    }

    $arStore['MAP'] = [
        'SHOW' =>
            (!empty($arStore['GPS_N']) || Type::isNumeric($arStore['GPS_N'])) &&
            (!empty($arStore['GPS_S']) || Type::isNumeric($arStore['GPS_S'])),
        'GPS' => [
            'N' => 0,
            'S' => 0
        ]
    ];

    if ($arStore['MAP']['SHOW']) {
        $arResult['MAP']['SHOW'] = true;
        $arStore['MAP']['GPS']['N'] = Type::toFloat(substr($arStore['GPS_N'],0,15));
        $arStore['MAP']['GPS']['S'] = Type::toFloat(substr($arStore['GPS_S'],0,15));

        $arResult['POSITION'] = [
            'LATITUDE' => $arStore['MAP']['GPS']['N'],
            'LONGITUDE' => $arStore['MAP']['GPS']['S']
        ];

        $arResult['PLACEMARKS'][] = [
            'LON' => $arStore['MAP']['GPS']['S'],
            'LAT' => $arStore['MAP']['GPS']['N'],
            'TEXT' => $arStore['TITLE']
        ];
    }

    unset($arStore['GPS_N']);
    unset($arStore['GPS_S']);
}

unset($rsStore);
unset($arStore);