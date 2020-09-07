<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!CModule::IncludeModule('iblock'))
    return;

/**
 * IBLOCK_TYPE
 */
$arTypes = CIBlockParameters::GetIBlockTypes();

$arIBLOCKS = array();
$arSectionsById = array();
$arElements = array();
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {

    /**
     * IBLOCK_ID
     */
    $rsIBLOCK = CIBlock::GetList(
        array(),
        array(
            'TYPE' => $arCurrentValues['IBLOCK_TYPE'],
            'ACTIVE' => 'Y'
        )
    );

    while ($IBLOCK = $rsIBLOCK->GetNext()) {
        $arIBLOCKS[$IBLOCK['ID']] = '['.$IBLOCK['ID'].'] '.$IBLOCK['NAME'];
    }
    unset($IBLOCK, $rsIBLOCK);

    if (!empty($arCurrentValues['IBLOCK_ID'])) {

        /**
         * SECTIONS_ID
         */
        $rsSections = CIBlockSection::GetList(
            array('SORT' => 'ASC'),
            array(
                'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
            )
        );

        while ($arSection = $rsSections->GetNext()) {
            $arSectionsById[$arSection['ID']] = '['.$arSection['ID'].'] '.$arSection['NAME'];
        }
        unset($arSection, $rsSections);

        /**
         * ELEMENTS_ID
         */
        if (!empty($arCurrentValues['SECTIONS_ID'])) {
            $rsElements = CIBlockElement::GetList(
                array('SORT' => 'ASC'),
                array(
                    'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
                    'SECTION_ID' => !empty($arCurrentValues['SECTIONS_ID']) ? $arCurrentValues['SECTIONS_ID'] : null
                )
            );

            while ($arElement = $rsElements->GetNext())
                $arElements[$arElement['ID']] = '[' . $arElement['ID'] . '] ' . $arElement['NAME'];
            unset($arElement, $rsElements);
        } else {
            $rsElements = CIBlockElement::GetList(
                array('SORT' => 'ASC'),
                array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'])
            );

            while ($arElement = $rsElements->GetNext())
                $arElements[$arElement['ID']] = '[' . $arElement['ID'] . '] ' . $arElement['NAME'];
            unset($arElement, $rsElements);
        }
    }
}


/**
 * BASE
 */
$arTemplateParameters = array(
    'SETTINGS_USE' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('A_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ),
    'LAZYLOAD_USE' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('A_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ),
    'IBLOCK_TYPE' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('A_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ),
    'IBLOCK_ID' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('A_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBLOCKS,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    )
);

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters += array(
        'SECTIONS_ID' => array(
            'PARENT' => 'BASE',
            'TYPE' => 'LIST',
            'NAME' => GetMessage('A_SECTIONS_ID'),
            'VALUES' => $arSectionsById,
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y',
            'REFRESH' => 'Y'
        ),
        'ELEMENTS_ID' => array(
            'PARENT' => 'BASE',
            'TYPE' => 'LIST',
            'NAME' => GetMessage('A_ELEMENTS_ID'),
            'VALUES' => $arElements,
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y'
        ),
        'ELEMENTS_COUNT' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('A_ELEMENTS_COUNT'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        )
    );
}

/**
 * VISUAL
 */
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters += array(
        'HEADER_SHOW' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('A_HEADER_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        )
    );
    if ($arCurrentValues['HEADER_SHOW'] == 'Y') {
        $arTemplateParameters += array(
            'HEADER_CENTER' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('A_HEADER_CENTER'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ),
            'HEADER' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('A_HEADER'),
                'TYPE' => 'STRING',
                'DEFAULT' => ''
            )
        );
    }
    $arTemplateParameters += array(
        'DESCRIPTION_SHOW' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('A_DESCRIPTION_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        )
    );
    if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
        $arTemplateParameters += array(
            'DESCRIPTION_CENTER' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('A_DESCRIPTION_CENTER'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ),
            'DESCRIPTION' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('A_DESCRIPTION'),
                'TYPE' => 'STRING',
                'DEFAULT' => ''
            )
        );
    }
    $arTemplateParameters += array(
        'BIG_FIRST_BLOCK' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('A_BIG_FIRST_BLOCK'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        )
    );
    ;$arTemplateParameters += array(
        'HEADER_ELEMENT_SHOW' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('A_HEADER_ELEMENT_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y'
        ),
        'DESCRIPTION_ELEMENT_SHOW' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('A_DESCRIPTION_ELEMENT_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        )
    );
}