<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Html;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?php $fDraw = function ($arItem, $iLevel) use (&$fDraw) { ?>
    <?php
    $sImage = null;

    if (!empty($arItem['IMAGE'])) {
        $sImage = CFile::ResizeImageGet($arItem['IMAGE'], array(
            'width' => 60,
            'height' => 30
        ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

        if (!empty($sImage)) {
            $sImage = $sImage['src'];
        } else {
            $sImage = null;
        }
    }

    $arItems = $arItem['ITEMS']
    ?>
    <div class="catalog-menu-submenu catalog-menu-submenu-<?= $iLevel ?>" data-role="menu">
        <div class="catalog-menu-submenu-wrapper">
            <div class="catalog-menu-submenu-header">
                <div class="catalog-menu-submenu-header-wrapper">
                    <div class="catalog-menu-submenu-header-wrapper-2">
                        <?php if (!empty($sImage)) { ?>
                            <div class="catalog-menu-submenu-image">
                                <div class="catalog-menu-submenu-image-wrapper" style="background-image: url('<?= $sImage ?>')"></div>
                            </div>
                        <?php } ?>
                        <div class="catalog-menu-submenu-title">
                            <div class="catalog-menu-submenu-title-wrapper">
                                <?= $arItem['TEXT'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="catalog-menu-submenu-delimiter"></div>
            <?php foreach ($arItems as $arItem) { ?>
                <?//print_r($arItem);?>
                <div class="catalog-menu-submenu-item" data-role="item">
                    <a href="<?= $arItem['LINK'] ?>" class="catalog-menu-submenu-item-text">
                        <?= $arItem['TEXT'] ?>
                    </a>
                    <?php if (!empty($arItem['ITEMS'])) $fDraw($arItem, $iLevel + 1) ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<div id="<?= $sTemplateId ?>" class="catalog-menu catalog-menu-vertical">
    <div class="catalog-menu-wrapper">
        <?php foreach ($arResult as $arItem) { ?>
            <?//print_r($arItem);?>

                <div class="catalog-menu-item <?if($arItem['SELECTED'] == 1):?><?= 'active'?><?endif?>" data-role="item">
                    <a href="<?= $arItem['LINK'] ?>" class="catalog-menu-item-text">
                        <?= $arItem['TEXT'] ?>
                    </a>
                    <?php if (!empty($arItem['ITEMS'])) $fDraw($arItem, 1) ?>
                </div>

        <?php } ?>
    </div>
    <script type="text/javascript">
        (function ($, api) {
            $(document).ready(function () {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                var items = root.find('[data-role=item]');
                var getItemMenu = function (item) {
                    return $(item).children('[data-role=menu]');
                };

                items.on('mouseover', function () {
                    var menu = getItemMenu(this);

                    menu.show().stop().animate({
                        'opacity': 1
                    }, 300);
                }).on('mouseout', function () {
                    var menu = getItemMenu(this);

                    menu.stop().animate({
                        'opacity': 0
                    }, 300, function () {
                        menu.hide();
                    });
                });
            });
        })(jQuery, intec);
    </script>
</div>