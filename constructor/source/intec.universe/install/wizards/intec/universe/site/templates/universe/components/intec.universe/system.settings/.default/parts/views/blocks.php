<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

return function ($sKey, $arProperty) use (&$arResult) { ?>
<?php
    $arBlocks = ArrayHelper::getValue($arProperty, 'blocks');

    if (!Type::isArray($arBlocks))
        $arBlocks = [];

    if (empty($arBlocks))
        return;
?>
    <div class="system-settings-property-blocks" data-role="property.blocks">
        <?php foreach ($arBlocks as $sBlockKey => $arBlock) { ?>
        <?php
            $bActive = $arProperty['value'][$sBlockKey]['active'];
            $sName = ArrayHelper::getValue($arBlock, 'name');
            $arTemplates = ArrayHelper::getValue($arBlock, 'templates');
            $sTemplate = $arProperty['value'][$sBlockKey]['template'];

            if (!Type::isArray($arTemplates))
                $arTemplates = [];
        ?>
            <div class="system-settings-property-block" data-role="property.block">
                <div class="system-settings-property-block-wrapper">
                    <div class="system-settings-property-block-header intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-5">
                        <div class="system-settings-property-block-name intec-grid-item">
                            <?= $sName ?>
                        </div>
                        <div class="system-settings-property-block-status intec-grid-item-auto">
                            <?= Html::hiddenInput('properties['.$sKey.']['.$sBlockKey.'][active]', 0) ?>
                            <?= Html::checkbox('properties['.$sKey.']['.$sBlockKey.'][active]', $bActive, [
                                'value' => 1,
                                'data' => [
                                    'role' => 'property.input',
                                    'type' => 'active'
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <?php if (!empty($arTemplates)) { ?>
                        <div class="system-settings-property-block-templates" data-role="property.block.templates">
                            <div class="system-settings-property-block-templates-wrapper">
                                <div class="system-settings-property-block-templates-title">
                                    <?= Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_VIEWS_BLOCKS_TEMPLATES_TITLE') ?>
                                </div>
                                <div class="<?= Html::cssClassFromArray([
                                    'system-settings-property-block-templates-list',
                                    'intec-grid' => [
                                        '',
                                        'wrap',
                                        'i-h-20',
                                        'i-v-10'
                                    ]
                                ]) ?>">
                                    <?php foreach ($arTemplates as $sTemplateKey => $arTemplate) { ?>
                                    <?php
                                        $sTemplateName = ArrayHelper::getValue($arTemplate, 'name');
                                        $sTemplateValue = ArrayHelper::getValue($arTemplate, 'value');
                                        $sTemplateImage = $this->GetFolder().'/images/properties/'.$sKey.'/'.$sBlockKey.'/'.$sTemplateValue.'.png';
                                        $bTemplateActive = $sTemplateValue == $sTemplate;
                                    ?>
                                        <div class="system-settings-property-block-template intec-grid-item-2" data-role="property.block.template">
                                            <label class="system-settings-property-block-template-wrapper">
                                                <?= Html::radio('properties['.$sKey.']['.$sBlockKey.'][template]', $bTemplateActive, [
                                                    'class' => 'system-settings-property-block-template-input',
                                                    'data' => [
                                                        'role' => 'property.input',
                                                        'type' => 'template'
                                                    ],
                                                    'value' => $sTemplateValue
                                                ]) ?>
                                                <span class="system-settings-property-block-template-image">
                                                    <span class="system-settings-property-block-template-image-wrapper">
                                                        <?= Html::img($arResult['LAZYLOAD']['USE'] ? $arResult['LAZYLOAD']['STUB'] : $sTemplateImage, [
                                                            'alt' => $sTemplateName,
                                                            'title' => $sTemplateName,
                                                            'loading' => 'lazy',
                                                            'data' => [
                                                                'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                                'original' => $arResult['LAZYLOAD']['USE'] ? $sTemplateImage : null
                                                            ]
                                                        ]) ?>
                                                    </span>
                                                </span>
                                                <span class="system-settings-property-block-template-name">
                                                    <?= $sTemplateName ?>
                                                </span>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php };