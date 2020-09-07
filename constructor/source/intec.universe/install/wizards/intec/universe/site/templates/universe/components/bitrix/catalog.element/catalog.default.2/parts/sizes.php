<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<div class="catalog-element-sizes">
    <div class="catalog-element-sizes-button intec-cl-text intec-cl-text-light-hover" onclick="universe.components.show(<?= JavaScript::toObject([
        'component' => 'intec.universe:main.widget',
        'template' => 'catalog.sizes.1',
        'parameters' => [
            'PATH' => $arVisual['SIZES']['PATH']
        ],
        'settings' => [
            'title' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SIZES'),
            'parameters' => [
                'width' => null
            ]
        ]
    ]) ?>);">
        <span class="catalog-element-sizes-button-text">
            <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_SIZES') ?>
        </span>
        <span class="catalog-element-sizes-button-icon">
            <i class="far fa-chevron-right"></i>
        </span>
    </div>
</div>