<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if (!Loader::includeModule('iblock'))
    return;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = array(
    'SHOW_TITLE' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_SHOW_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'TITLE' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_TITLE'),
        'TYPE' => 'STRING',
    ),
    'SHOW_DETAIL_LINK' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_SHOW_DETAIL_LINK'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'LIST_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => GetMessage('N_L_GALLERY_LIST_URL'),
        'TYPE' => 'STRING',
    ),
    'DETAIL_LINK_TEXT' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_DETAIL_LINK_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => GetMessage('N_L_GALLERY_DETAIL_LINK_TEXT_DEFAULT')
    ),
    'USE_CAROUSEL' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_USE_CAROUSEL'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'COLUMNS_COUNT' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_COLUMNS_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => 4
    )
);

if ($arCurrentValues['USE_CAROUSEL'] == 'Y') {
    $arTemplateParameters['ROWS_COUNT'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('N_L_GALLERY_ROWS_COUNT'),
        'TYPE' => 'STRING',
        'DEFAULT' => 1
    );
}