<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$module = 'sotbit.opengraph';
CModule::includeModule($module);

use Sotbit\Opengraph\OpengraphPageMetaTable;

$arFields['SITE_ID'] = WIZARD_SITE_ID;
$arFields['ACTIVE_OG'] = 'Y';
$arFields['NAME'] = WIZARD_SITE_DIR . 'help/delivery/';
$arFields['OG_TYPE'] = 'article';
$arFields['OG_TITLE'] = GetMessage('WZD_TITLE_OPGR');
$arFields['OG_DESCRIPTION'] = GetMessage('WZD_DESCRIPTION_OPGR');
$arFields['OG_IMAGE_SECURE_URL'] = WIZARD_SITE_DIR . 'help/delivery/';

$arFields['ACTIVE_TW'] = 'Y';
$arFields['TW_TITLE'] = GetMessage('WZD_TITLE_OPGR');
$arFields['TW_SITE'] = 'Site';
$arFields['TW_CREATOR'] = 'Creator';
$arFields['TW_DESCRIPTION'] = GetMessage('WZD_DESCRIPTION_OPGR');
$arFields['TW_IMAGE_ALT'] = GetMessage('WZD_TITLE_OPGR');

$arFields['OG_PROPS_ACTIVE'] = array (
    'OG_TYPE' => '1',
    'OG_TITLE' => '1',
    'OG_DESCRIPTION' => '1',
    'OG_IMAGE_SECURE_URL' => '1',
    'OG_IMAGE' => '1',
);

$arFields['TW_PROPS_ACTIVE'] = array (
    'TW_CARD' => '1',
    'TW_TITLE' => '1',
    'TW_SITE' => '1',
    'TW_CREATOR' => '1',
    'TW_DESCRIPTION' => '1',
    'TW_IMAGE_ALT' => '1',
    'TW_IMAGE' => '1',
);

$arFields['OG_PROPS_ACTIVE'] = serialize($arFields['OG_PROPS_ACTIVE']);
$arFields['TW_PROPS_ACTIVE'] = serialize($arFields['TW_PROPS_ACTIVE']);

$arFields['OG_IMAGE'] = $_SERVER["DOCUMENT_ROOT"].'/upload/sotbit_opengraph/delivery.png';
$arFields['TW_IMAGE'] = $_SERVER["DOCUMENT_ROOT"].'/upload/sotbit_opengraph/delivery.png';

$arFields['OG_IMAGE'] = \Sotbit\Opengraph\Helper\OpengraphHelper::saveImage($arFields['OG_IMAGE']);
$arFields['TW_IMAGE'] = \Sotbit\Opengraph\Helper\OpengraphHelper::saveImage($arFields['TW_IMAGE'], 'TW_IMAGE');

$result = OpengraphPageMetaTable::Add($arFields);

$arField['OG_TYPE'] = 'article';
$arField['OG_TITLE'] = GetMessage('WZD_TITLE_DEFAULT_OPGR');
$arField['OG_DESCRIPTION'] = GetMessage('WZD_DESCRIPTION_DEFAULT_OPGR');
$arField['OG_IMAGE_SECURE_URL'] = WIZARD_SITE_DIR;

$arField['TW_TITLE'] = GetMessage('WZD_TITLE_DEFAULT_OPGR');
$arField['TW_SITE'] = 'Site';
$arField['TW_CREATOR'] = 'Creator';
$arField['TW_DESCRIPTION'] = GetMessage('WZD_DESCRIPTION_DEFAULT_OPGR');
$arField['TW_IMAGE_ALT'] = GetMessage('WZD_TITLE_DEFAULT_OPGR');

$arField['OG_IMAGE'] = $_SERVER["DOCUMENT_ROOT"].'/upload/sotbit_opengraph/default.png';
$arField['TW_IMAGE'] = $_SERVER["DOCUMENT_ROOT"].'/upload/sotbit_opengraph/default.png';

$arField['OG_IMAGE'] = \Sotbit\Opengraph\Helper\OpengraphHelper::saveImage($arField['OG_IMAGE']);
$arField['TW_IMAGE'] = \Sotbit\Opengraph\Helper\OpengraphHelper::saveImage($arField['TW_IMAGE'], 'TW_IMAGE');

Bitrix\Main\Config\Option::set(SotbitOpengraph::MODULE_ID, 'OPENGRAPH_SETTINGS', serialize($arField), $arFields['SITE_ID']);
?>