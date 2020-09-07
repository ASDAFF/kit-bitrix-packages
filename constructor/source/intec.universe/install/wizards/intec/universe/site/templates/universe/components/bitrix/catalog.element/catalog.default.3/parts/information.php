<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="intec-grid intec-grid-a-v-center">
    <?php if ($arVisual['INFORMATION']['PAYMENT']) { ?>
        <div class="catalog-element-information-item intec-grid-item">
            <div class="catalog-element-information-item-button intec-cl-text-hover intec-cl-border-hover" onclick="universe.components.show(<?= JavaScript::toObject([
                'component' => 'intec.universe:main.widget',
                'template' => 'catalog.information.1',
                'parameters' => [
                    'PATH' => $arVisual['INFORMATION']['PAYMENT']['PATH']
                ],
                'settings' => [
                    'title' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_INFORMATION_PAYMENT'),
                    'parameters' => [
                        'width' => null
                    ]
                ]
            ]) ?>)">
                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_INFORMATION_PAYMENT') ?>
            </div>
        </div>
    <?php } ?>
    <?php if ($arVisual['INFORMATION']['SHIPMENT']) { ?>
        <div class="catalog-element-information-item intec-grid-item">
            <div class="catalog-element-information-item-button intec-cl-text-hover intec-cl-border-hover" onclick="universe.components.show(<?= JavaScript::toObject([
                'component' => 'intec.universe:main.widget',
                'template' => 'catalog.information.1',
                'parameters' => [
                    'PATH' => $arVisual['INFORMATION']['SHIPMENT']['PATH']
                ],
                'settings' => [
                    'title' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_INFORMATION_SHIPMENT'),
                    'parameters' => [
                        'width' => null
                    ]
                ]
            ]) ?>)">
                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_INFORMATION_SHIPMENT') ?>
            </div>
        </div>
    <?php } ?>
</div>
