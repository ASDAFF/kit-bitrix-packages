<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$arVisual = $arResult['VISUAL'];

$sPart = 'detail';

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
                <?php if ($arVisual['ADDITIONAL']['DETAIL']) { ?>
                    <div class="news-additional intec-grid-item-auto">
                        <?php if ($arVisual['TOP']['PAGES']['DETAIL']) {
                            include(__DIR__.'/parts/additional/top.php');
                        } ?>
                        <?php if ($arVisual['SUBSCRIBE']['PAGES']['DETAIL']) {
                            include(__DIR__.'/parts/additional/subscribe.php');
                        } ?>
                    </div>
                <?php } ?>
                <div class="news-content intec-grid-item">
                    <?php include(__DIR__.'/parts/detail.php') ?>
                </div>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>