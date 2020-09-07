<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 */

?>
<?php $vItems = function ($items) use (&$arVisual, &$arResult, &$sTemplateId) { ?>
    <?php if (!empty($items)) {
    ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-items',
                'intec-grid' => [
                    '',
                    'wrap',
                    'a-v-stretch'
                ]
            ]
        ]) ?>
            <?php foreach ($items as $arItem) {

                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                $arData = $arItem['DATA'];

            ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-item' => true,
                        'intec-grid-item' => [
                            $arVisual['COLUMNS'] => true,
                            '1200-3' => $arVisual['COLUMNS'] >= 3,
                            '1024-2' => true,
                            '650-1' => true
                        ]
                    ], true),
                    'data' => [
                        'price' => $arVisual['PRICE']['SHOW'] && !empty($arData['PRICE']) ? 'true' : 'false'
                    ]
                ]) ?>
                    <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                        <div class="widget-item-name">
                            <?= $arItem['NAME'] ?>
                        </div>
                        <div class="widget-item-delimiter"></div>
                        <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                            <div class="widget-item-description">
                                <?= $arItem['PREVIEW_TEXT'] ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['PRICE']['SHOW'] && !empty($arData['PRICE'])) { ?>
                            <div class="widget-item-price">
                                <span>
                                    <?= $arData['PRICE'] ?>
                                </span>
                                <?php if (!empty($arData['CURRENCY'])) { ?>
                                    <span>
                                        <?= $arData['CURRENCY'] ?>
                                    </span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php } ?>