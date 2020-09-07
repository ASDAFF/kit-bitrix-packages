<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

if ($this->startResultCache()) {
    $arResult = [
        'ITEMS' => []
    ];

    /** Получение значений списка свойства тегов */
    $sIBlock = ArrayHelper::getValue($arParams, 'IBLOCK_ID');
    $sPropertyTag = ArrayHelper::getValue($arParams, 'PROPERTY_TAG');

    $arPropertiesEnum = [];

    if (!empty($sIBlock) && !empty($sPropertyTag)) {
        $rsPropertiesEnum = CIBlockPropertyEnum::GetList(
            array('SORT' => 'ASC'),
            array('IBLOCK_ID' => $sIBlock, 'CODE' => $sPropertyTag)
        );

        while ($arPropertyEnum = $rsPropertiesEnum->GetNext()) {
            $arPropertiesEnum[$arPropertyEnum['XML_ID']] = $arPropertyEnum;
        }
    }

    $arResult['ITEMS'] = $arPropertiesEnum;

    /** Название переменной, для хранения тегов */
    $sVariableName = ArrayHelper::getValue($arParams, 'TAG_VARIABLE_NAME');
    $sVariableName = trim($sVariableName);
    $sVariableName = !empty($sVariableName) ? $sVariableName : 'tag';

    $arResult['VARIABLE_NAME'] = $sVariableName;

    $this->IncludeComponentTemplate();
}