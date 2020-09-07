<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Catalog;
/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

\Bitrix\Main\Loader::includeModule('iblock');
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), array("ACTIVE" => "Y"));

while ($arr = $rsIBlock->Fetch())
{
    $id = (int)$arr['ID'];
    if (isset($offersIblock[$id]))
        continue;
    $arIBlock[$id] = '['.$id.'] '.$arr['NAME'];
}

$arTemplateParameters['IBLOCK_ID'] = array(
    'NAME' => GetMessage('KIT_ORIGAMU_LEFT_MENU_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'MULTIPLE' => 'N',
    'VALUES' => $arIBlock
);

?>