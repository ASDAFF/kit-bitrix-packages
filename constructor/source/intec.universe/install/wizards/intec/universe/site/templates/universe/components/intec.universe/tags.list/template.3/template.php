<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arGet = Core::$app->request->get();

if (empty($arResult['TAGS']))
    return;

?>
<div class="ns-intec-universe c-tags-list c-tags-list-template-3" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <form method="get" data-role="form">
                <?php if (!empty($arGet)) { ?>
                    <?php foreach ($arGet as $key => $value) { ?>
                        <?php if ($key === $arParams['VARIABLE_TAGS']) continue ?>
                        <?= Html::hiddenInput($key, $value) ?>
                    <?php } ?>
                    <?php unset($key, $value) ?>
                <?php } ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'tags-list-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'i-3'
                        ],
                    ],
                    'data' => [
                        'role' => 'items'
                    ]
                ]) ?>
                    <?php foreach ($arResult['TAGS'] as $arTag) { ?>
                        <div class="tags-list-item intec-grid-item-auto">
                            <label class="tags-list-item-wrapper" data-role="item">
                                <?= Html::checkbox($arParams['VARIABLE_TAGS'].'[]', $arTag['SELECTED'], [
                                    'value' => $arTag['CODE']
                                ]) ?>
                                <span class="tags-list-item-text">
                                    <span class="tags-list-item-name">
                                        <?= $arTag['NAME'] ?>
                                    </span>
                                    <?php if ($arParams['COUNT'] === 'Y') { ?>
                                        <span class="tags-list-item-count">
                                            <?= $arTag['QUANTITY'] ?>
                                        </span>
                                    <?php } ?>
                                    <span class="tags-list-item-icon fal fa-times" data-role="button.delete"></span>
                                </span>
                            </label>
                        </div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </form>
        </div>
    </div>

    <?php include(__DIR__.'/parts/script.php') ?>
</div>