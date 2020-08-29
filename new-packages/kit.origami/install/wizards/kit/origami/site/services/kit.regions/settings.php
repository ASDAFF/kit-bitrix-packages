<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
    die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$module = 'kit.regions';
CModule::includeModule($module);
CModule::includeModule('main');

\Sotbit\Regions\Config\Option::set('MODE_LOCATION', 'Y', WIZARD_SITE_ID);
\Sotbit\Regions\Config\Option::set('FIND_USER_METHOD', 'ipgeobase', WIZARD_SITE_ID);
?>