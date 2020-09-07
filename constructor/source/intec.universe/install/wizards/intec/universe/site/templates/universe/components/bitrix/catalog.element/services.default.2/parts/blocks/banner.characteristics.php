<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arData
 */

$arCharacteristics = $arData['CHARACTERISTICS'];

?>
<div class="catalog-element-main-characteristics intec-grid intec-grid-wrap intec-grid-a-v-stretch">
    <?php foreach ($arCharacteristics as $arCharacteristic) { ?>
        <?= Html::beginTag('div', [
            'class' => [
                'catalog-element-main-characteristic',
                'intec-grid-item' => [
                    '5',
                    '1024-3',
                    '768-2',
                    '500-1'
                ]
            ]
        ]) ?>
            <div class="catalog-element-main-characteristic-wrapper">
                <div class="intec-grid intec-grid-a-v-center">
                    <?php if ($arCharacteristic['TYPE'] !== 'list') { ?>
                        <div class="intec-grid-item-auto">
                            <div class="catalog-element-main-characteristic-check">
                                <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 5L5 9L13 1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="intec-grid-item">
                            <div class="catalog-element-main-characteristic-name">
                                <?php if ($arCharacteristic['TYPE'] === 'string') { ?>
                                    <?= $arCharacteristic['VALUE'] ?>
                                <?php } else { ?>
                                    <?= $arCharacteristic['NAME'] ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="intec-grid-item">
                            <div class="catalog-element-main-characteristic-name">
                                <?= $arCharacteristic['NAME'].':' ?>
                            </div>
                            <div class="catalog-element-main-characteristic-values">
                                <?php foreach ($arCharacteristic['VALUES'] as $arValue) { ?>
                                    <div class="catalog-element-main-characteristic-value">
                                        <?= $arValue ?>
                                    </div>
                                <?php } ?>
                                <?php unset($arValue) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
</div>
<?php unset($arCharacteristics, $arCharacteristic) ?>