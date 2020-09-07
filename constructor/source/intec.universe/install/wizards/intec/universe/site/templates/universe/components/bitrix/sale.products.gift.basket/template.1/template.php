<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Security\Sign\Signer;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CAllMain $APPLICATION
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

$templateLibrary = ['popup'];
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject(
        $arResult['CURRENCIES'],
        false,
        true,
        true
    );
}

$templateData = [
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
];

unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = [
    'CONFIRM' => Loc::getMessage('C_SALE_PRODUCTS_GIFT_BASKET_TEMPLATE_1_TEMPLATE_SYSTEM_DELETE_CONFIRM')
];

$arGeneralParameters = [
    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
    'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
    'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
    'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
    '~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
    '~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
];

$sContainerName = 'sale-products-gift-container';

?>
<?= Html::beginTag('div', [
    'class' => [
        'ns-bitrix',
        'c-sale-products-gift-basket',
        'c-sale-products-gift-basket-template-1'
    ],
    'data-entity' => $sContainerName
]) ?>
    <?php if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS'])) {
        $arAreaIds = [];

        foreach ($arResult['ITEMS'] as &$arItem) {
            $uniqueId = $arItem['ID'].'_'.md5($this->randString().$component->getAction());
            $arAreaIds[$arItem['ID']] = $this->GetEditAreaId($uniqueId);
            $this->AddEditAction($uniqueId, $arItem['EDIT_LINK'], $elementEdit);
            $this->AddDeleteAction(
                $uniqueId,
                $arItem['DELETE_LINK'],
                $elementDelete,
                $elementDeleteParams
            );

            if (!empty($arParams['TEXT_LABEL_GIFT']))
                $arItem['LABEL_VALUE'] = $arParams['TEXT_LABEL_GIFT'];
            else
                $arItem['LABEL_VALUE'] = Loc::getMessage('C_SALE_PRODUCTS_GIFT_BASKET_TEMPLATE_1_TEMPLATE_LABEL_GIFT_DEFAULT');

            $arItem['LABEL'] = !empty($arItem['LABEL_VALUE']);
            $arItem['LABEL_ARRAY_VALUE'] = [
                'gift' => $arItem['LABEL_VALUE']
            ];
        }

        unset($arItem);

    ?>
        <!-- items-container -->
        <?= Html::beginTag('div', [
            'class' => [
                'sale-products-gift-basket-items',
                'intec-grid' => [
                    '',
                    'wrap',
                    'a-v-stretch'
                ]
            ],
            'data-entity' => 'items-row'
        ]) ?>
            <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'sale-products-gift-basket-item' => true,
                        'intec-grid-item' => [
                            $arParams['COLUMNS'] => true,
                            '1200-4' => $arParams['COLUMNS'] >= 5,
                            '1024-3' => $arParams['COLUMNS'] >= 4,
                            '768-2' => true,
                            '500-1' => true
                        ]
                    ], true)
                ]) ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.item',
                        'template.1', [
                            'RESULT' => [
                                'ITEM' => $arItem,
                                'AREA_ID' => $arAreaIds[$arItem['ID']]
                            ],
                            'PARAMS' => ArrayHelper::merge($arGeneralParameters, [
                                'SKU_PROPS' => $arResult['SKU_PROPS'][$arItem['IBLOCK_ID']]
                            ])
                        ],
                        $component,
                        ['HIDE_ICONS' => 'Y']
                    ) ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php unset($arItem) ?>
        <?= Html::endTag('div') ?>
        <!-- items-container -->
        <?php unset($arAreaIds) ?>
    <?php } else { ?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.item',
            'template.1',
            [],
            $component,
            ['HIDE_ICONS' => 'Y']
        ) ?>
    <?php } ?>
    <?php unset($arGeneralParameters) ?>
<?= Html::endTag('div') ?>
<?php

$signer = new Signer;
$signedTemplate = $signer->sign($templateName, 'sale.products.gift.basket');
$signedParams = $signer->sign(
    base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])),
    'sale.products.gift.basket'
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));

?>
<script type="text/javascript">
    var <?= $obName ?> = new JCSaleProductsGiftBasketComponent({
        'siteId': <?= JavaScript::toObject($component->getSiteId()) ?>,
        'componentPath': <?= JavaScript::toObject($componentPath) ?>,
        'deferredLoad': true,
        'initiallyShowHeader': <?= JavaScript::toObject(!empty($arResult['ITEM_ROWS'])) ?>,
        'currentProductId': <?= JavaScript::toObject((int)$arResult['POTENTIAL_PRODUCT_TO_BUY']['ID']) ?>,
        'template': <?= JavaScript::toObject($signedTemplate) ?>,
        'parameters': <?= JavaScript::toObject($signedParams) ?>,
        'container': <?= JavaScript::toObject($sContainerName) ?>
    });
</script>