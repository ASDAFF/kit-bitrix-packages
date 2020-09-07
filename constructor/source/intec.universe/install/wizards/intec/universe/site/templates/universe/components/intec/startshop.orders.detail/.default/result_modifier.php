<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 */

foreach ($arResult['ITEMS'] as &$arItem) {
    $arSection = Arrays::fromDBResult(CIBlockSection::GetList([], [
        'ID' => $arItem['IBLOCK_SECTION_ID'],
        'ACTIVE' => 'Y'
    ]))->indexBy('ID');

    $arItem['SECTION'] = ArrayHelper::getFirstValue($arSection->asArray());
}

foreach ($arResult['PROPERTIES'] as &$arProperty) {
    if ($arProperty['TYPE'] == 'B')
        $arProperty['VALUE'] = $arProperty['VALUE'] == 'Y' ? Loc::getMessage('SOD_DEFAULT_PROPERTY_Y') : Loc::getMessage('SOD_DEFAULT_PROPERTY_N');
}

unset($arItem, $arProperty);