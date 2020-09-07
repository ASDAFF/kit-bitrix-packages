<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
$arData = $arResult['DATA'];

?>
<?php include(__DIR__.'/parts/microdata.php') ?>
<div class="news-detail" id="<?= $sTemplateId ?>">
    <div class="news-detail-header news-detail-item">
        <div class="intec-grid intec-grid-a-v-center intec-grid-i-15 intec-grid-500-wrap">
            <?php if ($arVisual['DATE']['SHOW']) { ?>
                <div class="intec-grid-item-auto intec-grid-item-500-1">
                    <div class="news-detail-header-date">
                        <?= $arData['DATE'] ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($arVisual['TAGS']['SHOW'] && $arVisual['TAGS']['POSITION']['TOP']) { ?>
                <div class="intec-grid-item intec-grid-item-500-1">
                    <?php include(__DIR__.'/parts/tags.php') ?>
                </div>
            <?php } ?>
            <?php if ($arVisual['PRINT']['SHOW']) { ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'news-detail-print' => true,
                        'intec-grid-item' => !($arVisual['TAGS']['SHOW'] && $arVisual['TAGS']['POSITION']['TOP']),
                        'intec-grid-item-auto' => $arVisual['TAGS']['SHOW'] && $arVisual['TAGS']['POSITION']['TOP']
                    ], true)
                ]) ?>
                    <svg class="news-detail-print-icon" data-role="print" width="21" height="19" viewBox="0 0 21 19" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.7427 5.12061H0.742676V14.1206H4.74268V18.1206H16.7427V14.1206H20.7427V5.12061ZM14.7427 16.1206H6.74268V11.1206H14.7427V16.1206ZM17.7427 9.12061C17.1927 9.12061 16.7427 8.67061 16.7427 8.12061C16.7427 7.57061 17.1927 7.12061 17.7427 7.12061C18.2927 7.12061 18.7427 7.57061 18.7427 8.12061C18.7427 8.67061 18.2927 9.12061 17.7427 9.12061ZM16.7427 0.120605H4.74268V4.12061H16.7427V0.120605Z" />
                    </svg>
                <?= Html::endTag('div') ?>
            <?php } ?>
        </div>
    </div>
    <div class="news-detail-content news-detail-item">
        <?php if ($arVisual['PREVIEW']['SHOW']) { ?>
            <div class="news-detail-content-preview news-detail-content-item">
                <?= $arResult['PREVIEW_TEXT'] ?>
            </div>
        <?php } ?>
        <?php if ($arVisual['IMAGE']['SHOW']) { ?>
            <div class="news-detail-content-image news-detail-content-item">
                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $arResult['DETAIL_PICTURE']['SRC'], [
                    'alt' => '',
                    'loading' => 'lazy',
                    'data' => [
                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                        'original' => $arVisual['LAZYLOAD']['USE'] ? $arResult['DETAIL_PICTURE']['SRC'] : null
                    ]
                ]) ?>
            </div>
        <?php } ?>
        <div class="news-detail-content-detail news-detail-content-item">
            <?= $arResult['DETAIL_TEXT'] ?>
        </div>
        <?php if ($arVisual['TAGS']['SHOW'] && $arVisual['TAGS']['POSITION']['BOTTOM']) { ?>
            <div class="news-detail-content-tags news-detail-content-item">
                <?php include(__DIR__.'/parts/tags.php') ?>
            </div>
        <? } ?>
    </div>
    <?php if ($arVisual['ADDITIONAL']['NEWS']['SHOW']) { ?>
        <div class="news-detail-additional news-detail-item">
            <?php include(__DIR__.'/parts/news.php') ?>
        </div>
    <?php } ?>
    <?php if ($arVisual['BUTTON']['BACK']['SHOW']) { ?>
        <div class="news-detail-footer news-detail-item">
            <div class="intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-15">
                <?php if ($arVisual['BUTTON']['BACK']['SHOW']) { ?>
                    <div class="intec-grid-item-auto">
                        <div class="news-detail-back-wrap">
                            <a class="news-detail-back intec-cl-text-hover" href="<?= $arResult['LIST_PAGE_URL'] ?>">
                                <span class="news-detail-back-icon fal fa-angle-left"></span>
                                <span class="news-detail-back-text">
                                    <?= Loc::getMessage('C_NEWS_DETAIL_DEFAULT_1_TEMPLATE_BUTTON_BACK_TEXT_DEFAULT') ?>
                                </span>
                                <span class="intec-aligner"></span>
                            </a>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($arVisual['BUTTON']['SOCIAL']['SHOW']) { ?>
                    <div class="intec-grid-item">
                        <?php include(__DIR__.'/parts/social.php') ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <?php if ($arVisual['PRINT']['SHOW']) { ?>
        <script type="text/javascript">
            (function ($, api) {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                var print = $('[data-role="print"]', root);

                print.on('click', function () {
                    window.print();
                });
            })(jQuery, intec);
        </script>
    <?php } ?>
</div>