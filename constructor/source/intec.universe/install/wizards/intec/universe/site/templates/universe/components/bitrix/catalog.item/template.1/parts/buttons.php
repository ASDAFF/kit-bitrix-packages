<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $item
 * @var bool $bOffers
 * @var CAllMain $APPLICATION
 * @var CBitrixBasketComponent $component
 */

?>
<?= Html::beginTag('div', [
    'class' => 'catalog-item-button-container',
    'data-entity' => 'buttons-block'
]) ?>
    <?php if (!$bOffers) { ?>
        <?php if ($item['CAN_BUY']) { ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-item-button',
                    'catalog-item-button-add',
                    'intec-ui' => [
                        '',
                        'control-button',
                        'control-basket-button',
                        'mod-round-4',
                        'scheme-current'
                    ]
                ],
                'data' => [
                    'data-role' => 'item.button',
                    'basket-id' => $item['ID'],
                    'basket-action' => 'add',
                    'basket-state' => 'none'
                ]
            ]) ?>
                <span class="catalog-item-button-content intec-ui-part-content">
                    <?= FileHelper::getFileData(__DIR__.'/../svg/basket.button.add.svg') ?>
                    <span>
                        <?= Loc::getMessage('C_CATALOG_ITEM_TEMPLATE_1_TEMPLATE_BASKET_ADD') ?>
                    </span>
                </span>
                <span class="intec-ui-part-effect intec-ui-part-effect-bounce">
                    <span class="intec-ui-part-effect-wrapper">
                        <i></i><i></i><i></i>
                    </span>
                </span>
            <?= Html::endTag('div') ?>
            <?= Html::tag('div', Loc::getMessage('C_CATALOG_ITEM_TEMPLATE_1_TEMPLATE_BASKET_ADDED'), [
                'class' => [
                    'catalog-item-button',
                    'catalog-item-button-default',
                    'catalog-item-button-added',
                    'intec-ui' => [
                        '',
                        'control-button',
                        'mod-round-4',
                        'scheme-current',
                        'state-focus'
                    ]
                ],
                'data' => [
                    'basket-id' => $item['ID'],
                    'basket-state' => 'none'
                ]
            ]) ?>
        <?php } else { ?>
            <?php if ($arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && $item['CATALOG_SUBSCRIBE'] === 'Y') { ?>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.product.subscribe',
                    '.default', [
                        'PRODUCT_ID' => $item['ID'],
                        'BUTTON_ID' => $item['ID'].'_catalog_item_subscribe',
                        'BUTTON_CLASS' => Html::cssClassFromArray([
                            'catalog-item-button',
                            'catalog-item-button-default',
                            'intec-ui' => [
                                '',
                                'control-button',
                                'mod-round-4',
                                'scheme-current'
                            ]
                        ])
                    ],
                    $component,
                    ['HIDE_ICONS' => 'Y']
                ) ?>
            <?php } else { ?>
                <?= Html::tag('div', Loc::getMessage('C_CATALOG_ITEM_TEMPLATE_1_TEMPLATE_BASKET_UNAVAILABLE'), [
                    'class' => [
                        'catalog-item-button',
                        'catalog-item-button-default',
                        'catalog-item-button-unavailable',
                        'intec-ui' => [
                            '',
                            'control-button',
                            'mod-round-4',
                            'state-active'
                        ]
                    ]
                ]) ?>
            <?php } ?>
        <?php } ?>
    <?php } else { ?>
        <?= Html::tag('a', Loc::getMessage('C_CATALOG_ITEM_TEMPLATE_1_TEMPLATE_BASKET_DETAIL'), [
            'class' => [
                'catalog-item-button',
                'catalog-item-button-default',
                'intec-ui' => [
                    '',
                    'control-button',
                    'mod-round-4',
                    'scheme-current'
                ]
            ],
            'href' => $item['DETAIL_PAGE_URL']
        ]) ?>
    <?php } ?>
<?= Html::endTag('div') ?>
