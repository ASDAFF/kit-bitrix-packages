<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\core\net\Url;

/**
 * @var array $arParams
 * @var array $arResult
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));

Loc::loadMessages(__FILE__);

/**
 * @var Closure[] $arViews
 */
include(__DIR__.'/parts/views.php');

$sUrl = new Url(Core::$app->request->getUrl());
$sUrl->getQuery()->removeAt($arResult['VARIABLES']['VARIANT']);
$sUrl = $sUrl->build();

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-intec-universe',
        'c-system-settings',
        'c-system-settings-default'
    ],
    'data' => [
        'changed' => 'false',
        'expanded' => $arResult['ACTION'] === 'apply' || $arResult['ACTION'] === 'reset' ? 'true' : 'false'
    ]
]) ?>
    <!--noindex-->
    <div class="system-settings-overlay" data-role="overlay"></div>
    <div class="system-settings-blind" data-role="blind">
        <form class="system-settings-form" method="POST" data-role="form" action="<?= $sUrl ?>">
            <input type="hidden" name="<?= $arResult['VARIABLES']['ACTION'] ?>" value="apply" />
            <input type="hidden" name="category" value="<?= $arResult['CATEGORY'] ?>" />
            <div class="system-settings-buttons" data-role="buttons">
                <div class="system-settings-buttons-wrapper">
                    <div class="system-settings-button" data-role="button" data-show="settings">
                        <div class="system-settings-button-wrapper">
                            <div class="intec-aligner"></div>
                            <div class="system-settings-button-icon">
                                <i class="glyph-icon-settings"></i>
                            </div>
                        </div>
                    </div>
                    <div></div>
                    <? if (!empty($arResult['VARIANTS'])) { ?>
                        <div class="system-settings-button" data-role="button" data-show="preferences">
                            <div class="system-settings-button-wrapper">
                                <div class="intec-aligner"></div>
                                <div class="system-settings-button-icon">
                                    <i class="icon-preferences"></i>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                    <div class="system-settings-button" data-role="button" data-action="apply">
                        <div class="system-settings-button-wrapper">
                            <div class="intec-aligner"></div>
                            <div class="system-settings-button-icon">
                                <i class="fal fa-check"></i>
                            </div>
                            <div class="system-settings-button-text">
                                <?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_BUTTONS_APPLY') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="system-settings-content-tabs">
                <div class="system-settings-content-tab active" data-show="settings" data-role="content-tab">
                    <div class="system-settings-content">
                        <div class="system-settings-content-wrapper scrollbar-inner" data-role="scrollbar">
                            <ul class="system-settings-tabs intec-ui-mod-simple" role="tablist">
                                <?php foreach ($arResult['CATEGORIES'] as $sKey => $arCategory) { ?>
                                    <?php if (empty($arCategory['properties']) && $sKey !== 'templates') continue ?>
                                    <?= Html::beginTag('li', [
                                        'class' => Html::cssClassFromArray([
                                            'system-settings-tab' => true,
                                            'active' => $sKey === $arResult['CATEGORY']
                                        ], true),
                                        'data' => [
                                            'tab' => $sKey
                                        ]
                                    ]) ?>
                                    <a href="<?= '#'.$sTemplateId.'-'.$sKey ?>" class="system-settings-tab-wrapper" role="tab" data-toggle="tab">
                                        <div class="system-settings-tab-parts intec-grid intec-grid-nowrap intec-grid-i-h-5 intec-grid-a-v-center">
                                            <div class="system-settings-tab-part intec-grid-item-auto">
                                                <i class="system-settings-tab-icon"></i>
                                            </div>
                                            <div class="system-settings-tab-part intec-grid-item">
                                                <div class="system-settings-tab-text">
                                                    <?= $arCategory['name'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?= Html::endTag('li') ?>
                                <?php } ?>
                            </ul>
                            <div class="system-settings-containers tab-content">
                                <?php foreach ($arResult['CATEGORIES'] as $sKey => $arCategory) { ?>
                                    <?php if (empty($arCategory['properties']) && $sKey !== 'templates') continue ?>
                                    <?= Html::beginTag('div', [
                                        'id' => $sTemplateId.'-'.$sKey,
                                        'class' => Html::cssClassFromArray([
                                            'system-settings-container' => true,
                                            'tab-pane' => true,
                                            'active' => $sKey === $arResult['CATEGORY']
                                        ], true),
                                        'role' => 'tabpanel',
                                        'data' => [
                                            'container' => $sKey
                                        ]
                                    ]) ?>
                                    <?php if ($sKey !== 'templates') { ?>
                                        <div class="system-settings-properties intec-grid intec-grid-wrap intec-grid-a-v-stretch intec-grid-i-h-10 intec-grid-i-v-20" data-role="properties">
                                            <?php foreach ($arCategory['properties'] as $sPropertyKey => $arProperty) { ?>
                                                <?php
                                                $sName = ArrayHelper::getValue($arProperty, 'name');
                                                $sType = ArrayHelper::getValue($arProperty, 'type');
                                                $sView = ArrayHelper::getValue($arProperty, 'view');

                                                if (empty($sType))
                                                    continue;

                                                if (empty($sView))
                                                    $sView = $sType;

                                                $fView = ArrayHelper::getValue($arViews, $sView);

                                                if (empty($fView))
                                                    continue;

                                                $arProperty = ArrayHelper::merge([
                                                    'title' => true,
                                                    'grid' => [
                                                        'size' => 1
                                                    ]
                                                ], $arProperty);

                                                ?>
                                                <?= Html::beginTag('div', [
                                                    'class' => [
                                                        'system-settings-property',
                                                        'intec-grid-item'.(!empty($arProperty['grid']['size']) ? '-'.$arProperty['grid']['size'] : null)
                                                    ],
                                                    'data' => [
                                                        'role' => 'property',
                                                        'code' => $sPropertyKey,
                                                        'type' => $sType,
                                                        'view' => $sView
                                                    ]
                                                ]) ?>
                                                <?php if ($arProperty['title'] === true) { ?>
                                                    <div class="system-settings-property-name">
                                                        <?= $sName ?>
                                                    </div>
                                                <?php } ?>
                                                <div class="system-settings-property-content">
                                                    <?php $fView($sPropertyKey, $arProperty) ?>
                                                </div>
                                                <?= Html::endTag('div') ?>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <?php include(__DIR__.'/parts/templates.php') ?>
                                    <?php } ?>
                                    <?= Html::endTag('div') ?>
                                <?php } ?>
                            </div>
                            <div class="intec-clearfix"></div>
                        </div>
                    </div>
                    <div class="system-settings-panel">
                        <div class="system-settings-panel-parts intec-grid intec-grid-nowrap intec-grid-a-h-end intec-grid-a-v-center intec-grid-i-h-10">
                            <div class="system-settings-panel-part intec-grid-item-auto">
                                <button type="submit" class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-size-2" name="<?= $arResult['VARIABLES']['ACTION'] ?>" value="reset">
                                    <?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_BUTTONS_RESET') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <? if (!empty($arResult['VARIANTS'])) { ?>
                    <div class="system-settings-content-tab" data-show="preferences" data-role="content-tab">
                        <div class="system-settings-preferences">
                            <div class="system-settings-preferences-header-wrapper">
                                <div class="system-settings-preferences-header intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-a-h-center">
                                    <div class="intec-grid-item-2">
                                        <span class="system-settings-preferences-header-text"><?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_PREFERENCES_HEADER') ?></span>
                                    </div>
                                    <div class="intec-grid-item-auto">
                                        <button type="submit" class="system-settings-preferences-header-reset" name="<?= $arResult['VARIABLES']['ACTION'] ?>" value="reset">
                                            <i></i>
                                            <?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_PREFERENCES_RESET') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="system-settings-preferences-content">
                                <div class="system-settings-content-wrapper scrollbar-inner" data-role="scrollbar">
                                    <div class="system-settings-preferences-variants intec-grid intec-grid-wrap">
                                        <?php foreach ($arResult['VARIANTS'] as $sKey => $arVariant) { ?>
                                            <div class="system-settings-preferences-variant">
                                                <div class="intec-grid intec-grid-a-h-center intec-grid-i-h-15">
                                                    <div class="intec-grid-item-auto">
                                                        <a href="?<?=$arResult['VARIABLES']['VARIANT'] . '=' . $sKey?>" class="system-settings-preferences-variant-image" style="background-image:url(<?= $arVariant['preview'] ?>)">
                                                            <div class="system-settings-preferences-button-choose"><?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_PREFERENCES_BUTTON_CHOOSE') ?></div>
                                                        </a>
                                                    </div>
                                                    <div class="system-settings-preferences-variant-text-wrap">
                                                        <div class="system-settings-preferences-variant-text">
                                                            <a href="?<?=$arResult['VARIABLES']['VARIANT'] . '=' . $sKey?>" class="system-settings-preferences-variant-name intec-cl-text-hover"><?= $arVariant['name'] ?></a>
                                                            <div class="system-settings-preferences-variant-description"><?= $arVariant['description'] ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
        </form>
    </div>
    <!--/noindex-->
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div') ?>