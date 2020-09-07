<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arVisual
 */

?>
<div class="catalog-element-information catalog-element-block">
    <div class="intec-grid intec-grid-i-12 intec-grid-wrap">
        <?php if ($arVisual['INFORMATION']['STORES']['SHOW']) { ?>
            <div class="intec-grid-item-auto intec-grid-item-500-1">
                <div class="catalog-element-information-container">
                    <?= Html::tag('div', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_INFORMATION_STORES'), [
                        'class' => [
                            'catalog-element-information-name',
                            'intec-cl-border-hover'
                        ]
                    ]) ?>
                    <div class="catalog-element-information-popup">
                        <?php include(__DIR__.'/stores.php') ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($arVisual['INFORMATION']['SHIPMENT']['SHOW']) { ?>
            <div class="intec-grid-item-auto intec-grid-item-500-1">
                <?= Html::tag('div', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_INFORMATION_SHIPMENT'), [
                    'class' => [
                        'catalog-element-information-name',
                        'intec-cl-border-hover'
                    ],
                    'onclick' => 'universe.components.show('.JavaScript::toObject([
                        'component' => 'intec.universe:main.widget',
                        'template' => 'catalog.information.1',
                        'parameters' => [
                            'PATH' => $arVisual['INFORMATION']['SHIPMENT']['PATH']
                        ],
                        'settings' => [
                            'title' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_INFORMATION_SHIPMENT'),
                            'parameters' => [
                                'width' => null
                            ]
                        ]
                    ]).')'
                ]) ?>
            </div>
        <?php } ?>
        <?php if ($arVisual['INFORMATION']['PAYMENT']['SHOW']) { ?>
            <div class="intec-grid-item-auto intec-grid-item-500-1">
                <?= Html::tag('div', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_INFORMATION_PAYMENT'), [
                    'class' => [
                        'catalog-element-information-name',
                        'intec-cl-border-hover'
                    ],
                    'onclick' => 'universe.components.show('.JavaScript::toObject([
                            'component' => 'intec.universe:main.widget',
                            'template' => 'catalog.information.1',
                            'parameters' => [
                                'PATH' => $arVisual['INFORMATION']['PAYMENT']['PATH']
                            ],
                            'settings' => [
                                'title' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_INFORMATION_PAYMENT'),
                                'parameters' => [
                                    'width' => null
                                ]
                            ]
                        ]).')'
                ]) ?>
            </div>
        <?php } ?>
    </div>
</div>