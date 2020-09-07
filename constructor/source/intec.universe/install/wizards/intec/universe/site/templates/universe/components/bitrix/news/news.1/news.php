<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('iblock'))
    return;

$arVisual = $arResult['VISUAL'];

$sPart = 'news';

?>
<div class="ns-bitrix c-news c-news-news-1">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <?= Html::beginTag('div', [
                'class' => [
                    'news-body',
                    'intec-grid',
                    'intec-grid-i-20'
                ],
                'data' => [
                    'additional' => $arVisual['ADDITIONAL']['LIST'] ? 'true' : 'false'
                ]
            ]) ?>
                <?php if ($arVisual['ADDITIONAL']['LIST']) { ?>
                    <div class="news-additional intec-grid-item-auto">
                        <?php if ($arVisual['TAGS']['USE']) {
                            include(__DIR__.'/parts/additional/tags.php');
                        } ?>
                        <?php if ($arVisual['TOP']['PAGES']['LIST']) {
                            include(__DIR__.'/parts/additional/top.php');
                        } ?>
                        <?php if ($arVisual['SUBSCRIBE']['PAGES']['LIST']) {
                            include(__DIR__.'/parts/additional/subscribe.php');
                        } ?>
                    </div>
                <?php } ?>
                <div class="news-content intec-grid-item-auto">
                    <?php if ($arVisual['PANEL']['SHOW']) {
                        include(__DIR__.'/parts/panel.php');
                    } ?>
                    <?php include(__DIR__.'/parts/list.php') ?>
                </div>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>