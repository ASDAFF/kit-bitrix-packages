<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arCurrentValues
 */

if (!CModule::IncludeModule('iblock'))
    return;

/** IBLOCK_TYPE */
$IBlockType = CIBlockParameters::GetIBlockTypes();

$IBlock = array();
$sectionsById = array();
$elementsById = array();
$propertyCheckbox = array();
$propertyString = array();

if (!empty($IBlockType)) {

    /** IBLOCK_ID */
    $rsIBlock = CIBlock::GetList(
        array(),
        array(
            'TYPE' => $arCurrentValues['IBLOCK_TYPE'],
            'ACTIVE' => 'Y'
        )
    );

    while ($arIBlock = $rsIBlock->GetNext()) {
        $IBlock[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];
    }
    unset($arIBlock, $rsIBlock);

    if (!empty($arCurrentValues['IBLOCK_ID'])) {

        /** SECTIONS_ID */
        $rsSections = CIBlockSection::GetList(
            array('SORT' => 'ASC'),
            array(
                'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
            )
        );

        while ($arSection = $rsSections->GetNext()) {
            $sectionsById[$arSection['ID']] = '['.$arSection['ID'].'] '.$arSection['NAME'];
        }
        unset($arSection, $rsSections);

        /** ELEMENTS_ID */
        if (!empty($arCurrentValues['SECTIONS_ID'])) {
            $rsElements = CIBlockElement::GetList(
                array('SORT' => 'ASC'),
                array(
                    'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
                    'SECTION_ID' => !empty($arCurrentValues['SECTIONS_ID']) ? $arCurrentValues['SECTIONS_ID'] : null
                )
            );

            while ($arElement = $rsElements->GetNext())
                $elementsById[$arElement['ID']] = '[' . $arElement['ID'] . '] ' . $arElement['NAME'];
            unset($arElement, $rsElements);
        } else {
            $rsElements = CIBlockElement::GetList(
                array('SORT' => 'ASC'),
                array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'])
            );

            while ($arElement = $rsElements->GetNext())
                $elementsById[$arElement['ID']] = '[' . $arElement['ID'] . '] ' . $arElement['NAME'];
            unset($arElement, $rsElements);
        }

        /** IBLOCK_PROPERTIES */
        $rsIBlockProperties = CIBlockProperty::GetList(
            array(
                'SORT' => 'ASC'
            ),
            array(
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
                'IBLOCK_TYPE' => $arCurrentValues['IBLOCK_TYPE']
            )
        );

        while ($arIBlockProperty = $rsIBlockProperties->Fetch()) {
            if (empty($arIBlockProperty['CODE']))
                continue;

            if ($arIBlockProperty['PROPERTY_TYPE'] == 'L' && $arIBlockProperty['LIST_TYPE'] == 'C') {
                $propertyCheckbox[$arIBlockProperty['CODE']] = '['.$arIBlockProperty['CODE'].'] '.$arIBlockProperty['NAME'];
            }

            if ($arIBlockProperty['PROPERTY_TYPE'] == 'S' && $arIBlockProperty['MULTIPLE'] == 'N') {
                $propertyString[$arIBlockProperty['CODE']] = '['.$arIBlockProperty['CODE'].'] '.$arIBlockProperty['NAME'];
            }
        }
    }
}


/** BASE */
$arTemplateParameters = array(
    'IBLOCK_TYPE' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('ICONS_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $IBlockType,
        'REFRESH' => 'Y'
    ),
    'IBLOCK_ID' => array(
        'PARENT' => 'BASE',
        'NAME' => GetMessage('ICONS_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $IBlock,
        'REFRESH' => 'Y'
    )
);
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters += array(
        'SECTIONS_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('ICONS_SECTIONS_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $sectionsById,
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y',
            'REFRESH' => 'Y'
        ),
        'ELEMENTS_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('ICONS_ELEMENTS_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $elementsById,
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y'
        ),
        'ELEMENTS_COUNT' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('ICONS_ELEMENTS_COUNT'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ),
        'TARGET_BLANK' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('ICONS_TARGET_BLANK'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        )
    );
}

/** DATA_SOURCE */
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters += array(
        'PROPERTY_USE_LINK' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => GetMessage('ICONS_PROPERTY_USE_LINK'),
            'TYPE' => 'LIST',
            'VALUES' => $propertyCheckbox,
            'ADDITIONAL_VALUES' => 'Y'
        ),
        'PROPERTY_LINK' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => GetMessage('ICONS_PROPERTY_LINK'),
            'TYPE' => 'LIST',
            'VALUES' => $propertyString,
            'ADDITIONAL_VALUES' => 'Y'
        )
    );
}

/** VISUAL */
$arTemplateParameters += array(
    'SHOW_HEADER' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('ICONS_SHOW_HEADER'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    )
);
if ($arCurrentValues['SHOW_HEADER'] == 'Y') {
    $arTemplateParameters += array(
        'HEADER' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_HEADER'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ),
        'HEADER_POSITION' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_HEADER_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => array(
                'left' => GetMessage('ICONS_HEADER_POSITION_LEFT'),
                'center' => GetMessage('ICONS_HEADER_POSITION_CENTER')
            )
        )
    );
}
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters += array(
        'LINE_ELEMENTS_COUNT' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_LINE_ELEMENTS_COUNT'),
            'TYPE' => 'LIST',
            'VALUES' => array(
                3 => '3',
                4 => '4',
                5 => '5'
            )
        ),
        'VIEW' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => array(
                'default' => GetMessage('ICONS_VIEW_DEFAULT'),
                'center' => GetMessage('ICONS_VIEW_CENTER'),
                'with-description' => GetMessage('ICONS_VIEW_DESCRIPTION'),
                'left-float' => GetMessage('ICONS_VIEW_FLOAT')
            )
        ),
        'FONT_SIZE_HEADER' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_FONT_SIZE_HEADER'),
            'TYPE' => 'STRING'
        ),
        'FONT_STYLE_HEADER_BOLD' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_FONT_STYLE_HEADER_BOLD'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ),
        'FONT_STYLE_HEADER_ITALIC' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_FONT_STYLE_HEADER_ITALIC'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ),
        'FONT_STYLE_HEADER_UNDERLINE' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_FONT_STYLE_HEADER_UNDERLINE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ),
        'HEADER_TEXT_POSITION' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_HEADER_TEXT_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => array(
                'left' => GetMessage('ICONS_HEADER_TEXT_POSITION_LEFT'),
                'center' => GetMessage('ICONS_HEADER_TEXT_POSITION_CENTER'),
                'right' => GetMessage('ICONS_HEADER_TEXT_POSITION_RIGHT')
            )
        ),
        'HEADER_TEXT_COLOR' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_HEADER_TEXT_COLOR'),
            'TYPE' => 'STRING'
        )
    );
    if ($arCurrentValues['VIEW'] == 'with-description') {
        $arTemplateParameters += array(
            'FONT_SIZE_DESCRIPTION' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('ICONS_FONT_SIZE_DESCRIPTION'),
                'TYPE' => 'STRING'
            ),
            'FONT_STYLE_DESCRIPTION_BOLD' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('ICONS_FONT_STYLE_DESCRIPTION_BOLD'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ),
            'FONT_STYLE_DESCRIPTION_ITALIC' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('ICONS_FONT_STYLE_DESCRIPTION_ITALIC'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ),
            'FONT_STYLE_DESCRIPTION_UNDERLINE' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('ICONS_FONT_STYLE_DESCRIPTION_UNDERLINE'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ),
            'DESCRIPTION_TEXT_POSITION' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('ICONS_DESCRIPTION_TEXT_POSITION'),
                'TYPE' => 'LIST',
                'VALUES' => array(
                    'left' => GetMessage('ICONS_DESCRIPTION_TEXT_POSITION_LEFT'),
                    'center' => GetMessage('ICONS_DESCRIPTION_TEXT_POSITION_CENTER'),
                    'right' => GetMessage('ICONS_DESCRIPTION_TEXT_POSITION_RIGHT')
                )
            ),
            'DESCRIPTION_TEXT_COLOR' => array(
                'PARENT' => 'VISUAL',
                'NAME' => GetMessage('ICONS_DESCRIPTION_TEXT_COLOR'),
                'TYPE' => 'STRING'
            )
        );
    }
    $arTemplateParameters += array(
        'BACKGROUND_COLOR_ICON' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_BACKGROUND_COLOR_ICON'),
            'TYPE' => 'STRING'
        ),
        'BACKGROUND_OPACITY_ICON' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_BACKGROUND_OPACITY_ICON'),
            'TYPE' => 'STRING'
        ),
        'BACKGROUND_BORDER_RADIUS' => array(
            'PARENT' => 'VISUAL',
            'NAME' => GetMessage('ICONS_BACKGROUND_BORDER_RADIUS'),
            'TYPE' => 'STRING'
        )
    );
}