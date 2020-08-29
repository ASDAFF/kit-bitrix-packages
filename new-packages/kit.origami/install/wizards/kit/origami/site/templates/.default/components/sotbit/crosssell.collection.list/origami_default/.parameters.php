<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Web\Json;

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Config;
use Sotbit\Origami\Helper;

if (!Loader::includeModule('iblock'))
	return;

if (!Loader::includeModule('sotbit.origami'))
    return;


$arVaraintListView = array_merge(array("ADMIN" => GetMessage('CP_BC_TPL_FROM_ADMIN')), Helper\Config::getVariantListView());

$arTemplateParameters['VARIANT_LIST_VIEW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage("SOTBIT_CROSSSELL_VARIANT_LIST_VIEW"),
    'TYPE' => 'LIST',
    'VALUES' => $arVaraintListView,
    'DEFAULT' => 'template_3',
    'ADDITIONAL_VALUES' => 'N'
);

$arActionProducts = array_merge(array("ADMIN" => GetMessage('CP_BC_TPL_FROM_ADMIN')), Helper\Config::getActionProducts());

$arTemplateParameters['ACTION_PRODUCTS'] = array(
    'PARENT' => 'ACTION_SETTINGS',
    'NAME' => GetMessage('SOTBIT_CROSSSELL_ACTION_PRODUCTS'),
    'TYPE' => 'LIST',
    'MULTIPLE' => 'Y',
    'DEFAULT' => 'ADMIN',
    'VALUES' => $arActionProducts
);