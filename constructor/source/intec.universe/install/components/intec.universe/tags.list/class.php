<?php

use intec\Core;
use intec\core\bitrix\components\IBlock;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

class IntecTagsListComponent extends IBlock
{
    /**
     * @inheritdoc
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!Type::isArray($arParams))
            $arParams = [];

        $arParams = ArrayHelper::merge([
            'IBLOCK_TYPE' => null,
            'IBLOCK_ID' => null,
            'SECTION_ID' => null,
            'SECTION_SUBSECTIONS' => null,
            'PROPERTY' => null,
            'COUNT' => 'Y',
            'USED' => 'Y',
            'FILTER_NAME' => null,
            'VARIABLE_TAGS' => null
        ], $arParams);

        if (empty($arParams['FILTER_NAME']))
            $arParams['FILTER_NAME'] = 'arrFilter';

        if (empty($arParams['VARIABLE_TAGS']))
            $arParams['VARIABLE_TAGS'] = 'tags';

        return $arParams;
    }

    /**
     * @inheritdoc
     */
    public function executeComponent()
    {
        global $USER;

        $this->arResult = [
            'TAGS' => []
        ];

        $oRequest = Core::$app->request;
        $arParams = $this->arParams;
        $arResult = $this->arResult;

        $sCacheId = $USER->GetGroups();

        if (isset($GLOBALS[$arParams['FILTER_NAME']]) && Type::isArray($GLOBALS[$arParams['FILTER_NAME']]))
            $sCacheId = serialize($GLOBALS[$arParams['FILTER_NAME']]);

        if ($this->startResultCache(false, $sCacheId)) {
            $this->setIBlockType($arParams['IBLOCK_TYPE']);
            $this->setIBlockId($arParams['IBLOCK_ID']);

            $arIBlock = $this->getIBlock();

            if (!empty($arIBlock) && $arIBlock['ACTIVE'] === 'Y' && !empty($arParams['PROPERTY'])) {
                $arProperty = CIBlockProperty::GetList([], [
                    'IBLOCK_ID' => $arIBlock['ID'],
                    'ACTIVE' => 'Y',
                    'CODE' => $arParams['PROPERTY'],
                    'PROPERTY_TYPE' => 'L'
                ])->Fetch();

                if (!empty($arProperty)) {


                    $arEnums = Arrays::fromDBResult(CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], [
                        'IBLOCK_ID' => $arIBlock['ID'],
                        'PROPERTY_ID' => $arProperty['ID']
                    ]));

                    $arQuantity = [];

                    if ($arParams['USED'] === 'Y' || $arParams['COUNT'] === 'Y') {
                        $arFilter = [];

                        if (isset($GLOBALS[$arParams['FILTER_NAME']]) && Type::isArray($GLOBALS[$arParams['FILTER_NAME']]))
                            $arFilter = $GLOBALS[$arParams['FILTER_NAME']];

                        $arFilter['IBLOCK_ID'] = $arIBlock['ID'];
                        $arFilter['ACTIVE'] = 'Y';
                        $arFilter['ACTIVE_DATE'] = 'Y';
                        $arFilter['CHECK_PERMISSIONS'] = 'Y';

                        if (!empty($arParams['SECTION_ID']) || Type::isNumeric($arParams['SECTION_ID']) || $arParams['SECTION_ID'] === false) {
                            $arFilter['SECTION_ID'] = $arParams['SECTION_ID'];
                            $arFilter['SECTION_SCOPE'] = 'IBLOCK';

                            if ($arParams['SECTION_SUBSECTIONS'] === 'Y' || $arParams['SECTION_SUBSECTIONS'] === 'A')
                                $arFilter['INCLUDE_SUBSECTIONS'] = 'Y';

                            if ($arParams['SECTION_SUBSECTIONS'] === 'A')
                                $arFilter['SECTION_GLOBAL_ACTIVE'] = 'Y';
                        }

                        $arValues = Arrays::fromDBResult(CIBlockElement::GetPropertyValues(
                            $arIBlock['ID'],
                            $arFilter,
                            false,
                            ['ID' => $arProperty['ID']]
                        ));

                        foreach ($arValues as $arValue) {
                            $mValues = $arValue[$arProperty['ID']];

                            if (!empty($mValues)) {
                                if (Type::isArray($mValues)) {
                                    foreach ($mValues as $iValue) {
                                        if (!ArrayHelper::keyExists($iValue, $arQuantity))
                                            $arQuantity[$iValue] = 0;

                                        $arQuantity[$iValue]++;
                                    }
                                } else {
                                    if (!ArrayHelper::keyExists($mValues, $arQuantity))
                                        $arQuantity[$mValues] = 0;

                                    $arQuantity[$mValues]++;
                                }
                            }
                        }

                        unset($iValue);
                        unset($mValues);
                        unset($arValue);
                        unset($arValues);
                        unset($arFilter);
                    }

                    foreach ($arEnums as $arEnum) {
                        $iQuantity = 0;

                        if ($arParams['USED'] === 'Y' && !ArrayHelper::keyExists($arEnum['ID'], $arQuantity))
                            continue;

                        if ($arParams['COUNT'] === 'Y' && ArrayHelper::keyExists($arEnum['ID'], $arQuantity))
                            $iQuantity = $arQuantity[$arEnum['ID']];

                        $arResult['TAGS'][] = [
                            'ID' => $arEnum['ID'],
                            'CODE' => $arEnum['XML_ID'],
                            'QUANTITY' => $iQuantity,
                            'NAME' => $arEnum['VALUE']
                        ];
                    }
                }
            }

            $this->arResult = $arResult;
            $this->endResultCache();
        }

        foreach ($this->arResult['TAGS'] as &$arTag)
            $arTag['SELECTED'] = false;

        unset($arResult);
        unset($arTag);

        $arValues = $oRequest->get($arParams['VARIABLE_TAGS']);

        if (!empty($arValues) && Type::isArray($arValues)) {
            foreach ($this->arResult['TAGS'] as &$arTag)
                if (ArrayHelper::isIn($arTag['CODE'], $arValues))
                    $arTag['SELECTED'] = true;

            unset($arTag);
        }

        unset($arValues);

        $arFilter = [];

        if (isset($GLOBALS[$arParams['FILTER_NAME']]))
            $arFilter = $GLOBALS[$arParams['FILTER_NAME']];

        if (!Type::isArray($arFilter))
            $arFilter = [];

        if (!empty($arParams['PROPERTY'])) {
            $arCondition = [];

            foreach ($this->arResult['TAGS'] as $arTag)
                if ($arTag['SELECTED'])
                    $arCondition[] = $arTag['ID'];

            if (!empty($arCondition))
                $arFilter['PROPERTY_'.$arParams['PROPERTY']] = $arCondition;
        }

        $GLOBALS[$arParams['FILTER_NAME']] = $arFilter;
        $this->includeComponentTemplate();

        return [
            'FILTER' => $arFilter,
            'TAGS' => $this->arResult['TAGS']
        ];
    }
}