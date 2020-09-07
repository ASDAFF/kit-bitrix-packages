<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $arData
 */

foreach ($arResult['OFFERS'] as &$arOffer) {
    $arOfferData = [
        'ARTICLE' => [],
        'GALLERY' => []
    ];

    /** Артикул предложений */
    if (!empty($arParams['OFFERS_PROPERTY_ARTICLE'])) {
        $arProperty = ArrayHelper::getValue($arOffer, [
            'PROPERTIES',
            $arParams['OFFERS_PROPERTY_ARTICLE']
        ]);

        if (!empty($arProperty['VALUE']) && $arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N') {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arOffer,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE']))
                $arOfferData['ARTICLE'] = [
                    'NAME' => $arProperty['NAME'],
                    'VALUE' => $arProperty['DISPLAY_VALUE']
                ];
        }

        unset($arProperty);
    }

    $arOffer['DATA'] = $arOfferData;

    unset($arOfferData);
}

unset($arOffer);