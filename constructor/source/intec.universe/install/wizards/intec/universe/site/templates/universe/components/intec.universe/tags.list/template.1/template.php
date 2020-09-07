<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arValues = Core::$app->request->get();

if (empty($arResult['TAGS']))
    return;

?>
<div id="<?= $sTemplateId ?>" class="ns-intec-universe c-tags-list c-tags-list-template-1">
    <div class="tags-list-wrapper intec-content intec-content-visible">
        <div class="tags-list-wrapper-2 intec-content-wrapper">
            <form method="get" data-role="form">
                <?php foreach ($arValues as $sKey => $mValue) { ?>
                    <?php if ($sKey == $arParams['VARIABLE_TAGS']) continue ?>
                    <?= Html::hiddenInput($sKey, $mValue) ?>
                <?php } ?>
                <div class="tags-list-items intec-grid intec-grid-wrap intec-grid-i-h-4 intec-grid-i-v-2 intec-grid-a-h-start intec-grid-a-v-start" data-role="items">
                    <?php foreach ($arResult['TAGS'] as $arTag) { ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'tags-list-item',
                                'intec-grid-item-auto'
                            ],
                            'data' => [
                                'code' => $arTag['CODE'],
                                'role' => 'item'
                            ]
                        ]) ?>
                            <label class="tags-list-item-wrapper">
                                <?= Html::checkbox($arParams['VARIABLE_TAGS'].'[]', $arTag['SELECTED'], [
                                    'value' => $arTag['CODE']
                                ]) ?>
                                <div class="tags-list-item-button">
                                    <span class="tags-list-item-name"><?= Html::encode($arTag['NAME']) ?></span>
                                    <?php if ($arParams['COUNT'] === 'Y') { ?>
                                        <span class="tags-list-item-quantity"><?= $arTag['QUANTITY'] ?></span>
                                    <?php } ?>
                                </div>
                            </label>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
    <?php include(__DIR__.'/parts/script.php') ?>
</div>