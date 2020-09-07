<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<ul class="catalog-element-tabs nav nav-tabs intec-ui-mod-simple" data-animation="<?= $arVisual['TABS']['ANIMATION'] ? 'true' : 'false' ?>" role="tablist">
    <?php foreach ($arResult['SECTIONS'] as $sCode => $arSection) { ?>
        <li class="<?= Html::cssClassFromArray([
            'catalog-element-tab' => true,
            'active' => $arSection['ACTIVE']
        ], true) ?>">
            <?= Html::tag('a', $arSection['NAME'], [
                'href' => !empty($arSection['URL']) ? ($arSection['ACTIVE'] ? null : $arSection['URL']) : '#'.$sTemplateId.'-'.$arSection['CODE'],
                'role' => 'tab',
                'data' => [
                    'toggle' => empty($arSection['URL']) ? 'tab' : null
                ]
            ]) ?>
        </li>
    <?php } ?>
</ul>
<div class="catalog-element-sections catalog-element-sections-tabs tab-content">
    <?php foreach ($arResult['SECTIONS'] as $sCode => $arSection) { ?>
        <?php if (!empty($arSection['URL']) && !$arSection['ACTIVE']) continue ?>
        <?= Html::beginTag('div', [
            'id' => empty($arSection['URL']) ? $sTemplateId.'-'.$arSection['CODE'] : null,
            'class' => Html::cssClassFromArray([
                'catalog-element-section' => true,
                'tab-pane' => true,
                'active' => $arSection['ACTIVE']
            ], true),
            'role' => 'tabpanel'
        ]) ?>
            <div class="catalog-element-section-content" data-role="section.content">
                <?php include(__DIR__.'/sections/'.$arSection['CODE'].'.php') ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
</div>
<?php

unset($sCode, $arSection);