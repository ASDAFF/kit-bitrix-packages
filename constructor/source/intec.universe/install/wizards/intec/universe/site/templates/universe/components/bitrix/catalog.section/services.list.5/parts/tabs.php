<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var Closure $vItems()
 */

?>
<div class="catalog-section-tabs">
    <div class="intec-content">
        <div class="catalog-section-tabs-wrapper">
            <div class="catalog-section-tabs-items" data-align="<?= $arVisual['TABS']['POSITION'] ?>">
                <?= Html::tag('div', Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_TEMPLATE_TABS_ALL'), [
                    'class' => [
                        'catalog-section-tabs-item',
                        'intec-cl-background',
                        'intec-cl-background-light-hover'
                    ],
                    'data' => [
                        'role' => 'tabs.item',
                        'id' => 'all',
                        'active' => 'true'
                    ]
                ]) ?>
                <?php foreach ($arResult['TABS'] as $sKey => $sTab) { ?>
                    <?= Html::tag('div', $sTab, [
                        'class' => [
                            'catalog-section-tabs-item',
                            'intec-cl-background-light-hover'
                        ],
                        'data' => [
                            'role' => 'tabs.item',
                            'id' => $sKey,
                            'active' => 'false'
                        ]
                    ]) ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="catalog-section-tabs-content" data-role="tabs.content">
    <?php $vItems($arResult['ITEMS']) ?>
</div>