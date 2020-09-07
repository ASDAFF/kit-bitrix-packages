<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sImage
 * @var int $iLevel
 * @var array $arItem
 * @var array $arItems
 * @var boolean $bIsIBlock
 */

$bFirstItem = true;
?>
<div class="menu-submenu menu-submenu-catalog menu-submenu-<?= $iLevel ?> 22" data-role="menu">
    <div class="menu-submenu-wrapper" data-role="items">
        <?php foreach ($arItems as $arItem) { ?>
        <?php
            $bActive = $arItem['ACTIVE'];
            $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
            $bSelected = Type::toBoolean($bSelected);

            $bSubmenu = !empty($arItem['ITEMS']) && !$bIsIBlock;

            $sUrl = $bActive ? null : $arItem['LINK'];
            $sTag = $bActive ? 'div' : 'a';
        ?>
            <div class="menu-submenu-item<?= $bSelected ? ' menu-submenu-item-active' : null ?>" data-role="item">
                <?= Html::beginTag($sTag, array(
                    'class' => 'menu-submenu-item-text intec-cl-text-hover'.($bSelected ? ' intec-cl-text' : null),
                    'href' => $sUrl
                )); ?>
                    <?= Html::encode($arItem['TEXT']) ?>
                <?= Html::endTag($sTag) ?>
                <?php if ($bSubmenu) { ?>
                    <div class="menu-submenu-item-arrow far fa-angle-right"></div>
                <?php } ?>
                <?php if ($bSubmenu) $fDraw($arItem, $iLevel + 1) ?>
            </div>
            <?php $bFirstItem = false ?>
        <?php } ?>
    </div>
    <div class="intec-ui-clear"></div>
</div>