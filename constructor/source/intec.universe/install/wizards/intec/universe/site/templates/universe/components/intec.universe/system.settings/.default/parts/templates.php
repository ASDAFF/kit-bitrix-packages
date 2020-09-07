<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arCategory
 * @var CBitrixComponentTemplate $this
 */

?>
<div class="system-settings-templates">
    <div class="system-settings-templates-title">
        <?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_TEMPLATES_TITLE') ?>
    </div>
    <div class="system-settings-templates-list">
        <?php foreach ($arResult['TEMPLATES'] as $oTemplate) { ?>
        <?php $bActive = $oTemplate->id === $arResult['TEMPLATE']->id ?>
            <div class="system-settings-template" data-active="<?= $bActive ? 'true' : 'false' ?>">
                <div class="system-settings-template-wrapper intec-grid intec-grid-nowrap intec-grid-i-h-5 intec-grid-a-v-center">
                    <div class="system-settings-template-name intec-grid-item intec-grid-item-shrink-1">
                        <?= $oTemplate->name ?>
                    </div>
                    <div class="system-settings-template-link intec-grid-item-auto">
                        <a target="_blank" href="<?= '/bitrix/admin/constructor_builds_templates_editor.php?build='.$oTemplate->buildId.'&template='.$oTemplate->id.'&lang='.LANGUAGE_ID ?>">
                            <?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_TEMPLATES_TEMPLATE_EDIT') ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="system-settings-templates-description">
        <div class="system-settings-templates-description-picture">
            <?= Html::img($arResult['LAZYLOAD']['USE'] ? $arResult['LAZYLOAD']['STUB'] : $this->GetFolder().'/images/templates.png', [
                'alt' => Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_TEMPLATES_TITLE'),
                'title' => Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_TEMPLATES_TITLE'),
                'loading' => 'lazy',
                'data' => [
                    'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arResult['LAZYLOAD']['USE'] ? $this->GetFolder().'/images/templates.png' : null
                ]
            ]) ?>
        </div>
        <div class="system-settings-templates-description-text">
            <?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_TEMPLATES_DESCRIPTION') ?>
        </div>
    </div>
</div>
