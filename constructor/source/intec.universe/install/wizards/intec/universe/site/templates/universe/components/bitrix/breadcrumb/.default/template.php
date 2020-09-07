<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

include(__DIR__.'/result_modifier.php');

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 * @var Arrays $oSections
 */

if (!Loader::includeModule('intec.core'))
    return;

global $APPLICATION;

if (defined('EDITOR'))
    $arResult = [[
        'TITLE' => Loc::getMessage('BREADCRUMB_PAGE_TITLE'),
        'LINK' => ''
    ]];

if (empty($arResult))
    return '';

$sTemplateId = Core::$app->security->generateRandomString(8);
$arRender = [];
$arResult = ArrayHelper::merge(
    [[
        'TITLE' => Loc::getMessage('BREADCRUMB_MAIN_TITLE'),
        'LINK' => SITE_DIR
    ]],
    $arResult
);

$iCount = count($arResult);
$iIndex = 0;

foreach ($arResult as $arItem) {
    $sTitle = Html::encode($arItem['TITLE']);
    $sLink = $arItem['LINK'];

    if ($arItem['LINK'] != '') {
        $arSectionCurrent = $oSections->where(function ($sKey, $arSection) use (&$arItem) {
            return $arSection['SECTION_PAGE_URL'] == $arItem['LINK'];
        })->getFirst();

        $arRenderMenu = [];

        if (!empty($arSectionCurrent)) {
            $arSections = $oSections->where(function ($sKey, $arSection) use (&$arSectionCurrent) {
                return $arSection['IBLOCK_SECTION_ID'] == $arSectionCurrent['IBLOCK_SECTION_ID'];
            })->asArray();

            if (!empty($arSections)) {
                $arRenderMenu[] = Html::beginTag('div', [
                    'class' => 'breadcrumb-menu',
                    'data-control' => 'menu'
                ]);
                $arRenderMenu[] = Html::beginTag('div', [
                    'class' => 'breadcrumb-menu-wrapper'
                ]);

                foreach ($arSections as $arSection) {
                    $arRenderMenu[] = Html::tag('a', $arSection['NAME'], [
                        'class' => 'breadcrumb-menu-item intec-cl-text-hover',
                        'href' => $arSection['SECTION_PAGE_URL']
                    ]);
                }

                $arRenderMenu[] = Html::endTag('div');
                $arRenderMenu[] = Html::endTag('div');
            }
        }

        $arRender[] = '
            <div class="breadcrumb-item" data-control="item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                <a href="'.$sLink.'" title="'.$sTitle.'" data-control="link" class="breadcrumb-link intec-cl-text-hover" itemprop="item">
                    <span itemprop="name">'.$sTitle.'</span>
                    <meta itemprop="position" content="'.($iIndex + 1).'">'.
                    (!empty($arRenderMenu) ? '<i class="far fa-angle-down"></i>' : null).
                '</a>'.
                implode('', $arRenderMenu).
            '</div>';
    } else {
        $arRender[] = '
            <div class="breadcrumb-item intec-cl-text" data-control="item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                <div itemprop="item">
                    <span itemprop="name">'.$sTitle.'</span>
                    <meta itemprop="position" content="'.($iIndex + 1).'">
                </div>
            </div>';
    }

    $iIndex++;
}

return
    '<div id="'.$sTemplateId.'" class="ns-bitrix c-breadcrumb c-breadcrumb-default">'.
        '<div class="breadcrumb-wrapper intec-content intec-content-visible">'.
            '<div class="breadcrumb-wrapper-2 intec-content-wrapper" itemscope="" itemtype="http://schema.org/BreadcrumbList">'.
                implode('<span class="breadcrumb-separator">/</span>', $arRender)
            .'</div>'.
        '</div>'
        .Html::script('(function ($) {
            $(document).ready(function () {
                var root;
                
                root = $('.JavaScript::toObject('#'.$sTemplateId).');
                root.find(\'[data-control="item"]\').each(function () {
                    var item;
                    var link;
                    var menu;
                    
                    item = $(this);
                    link = item.find(\'[data-control="link"]\');
                    menu = item.find(\'[data-control="menu"]\');
                    
                    item.on(\'mouseover\', function () {
                        link.addClass(\'intec-cl-text\');
                        menu.css({\'display\': \'block\'}).stop().animate({
                            \'opacity\': 1
                        }, 300);
                    }).on(\'mouseout\', function () {
                        link.removeClass(\'intec-cl-text\');
                        menu.stop().animate({
                            \'opacity\': 0
                        }, 300, function () {
                            menu.css({\'display\': \'none\'});
                        });
                    });
                });
            });
        })(jQuery)', [
            'type' => 'text/javascript'
        ])
    .'</div>';