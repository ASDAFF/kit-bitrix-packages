<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="news-detail-links">
    <div class="intec-content news-detail-links-wrapper">
        <div class="intec-content-wrapper news-detail-links-wrapper-2">
            <?= Html::beginTag('div', [
                'class' => [
                    'news-detail-links-wrapper-3',
                    'intec-grid' => [
                        '',
                        '400-wrap',
                        'a-h' => [
                            'between',
                            '400-center',
                            ],
                        'a-v-center'
                    ]
                ]
            ]) ?>
                <a class="news-detail-links-return intec-cl-text intec-cl-text-light-hover intec-grid-item-auto" href="<?= $arResult['LIST_PAGE_URL'] ?>">
                    <span class="news-detail-links-return-icon far fa-angle-left"></span>
                    <span class="news-detail-links-return-text">
                        <?= $arBlock['BUTTON'] ?>
                    </span>
                </a>
                <?php if ($arBlock['SOCIAL_SHOW']) { ?>
                    <div class="news-detail-links-social intec-grid-item-auto intec-grid intec-grid-a-v-center">
                        <span class="news-detail-links-social-title">
                            <?= $arBlock['TITLE'] ?>
                        </span>
                        <div class="news-detail-links-social-items">
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:main.share',
                                'flat',
                                array(
                                    'HANDLERS' => $arBlock['HANDLERS'],
                                    'PAGE_URL' => $arResult['~DETAIL_PAGE_URL'],
                                    'PAGE_TITLE' => $arResult['~NAME'],
                                    'SHORTEN_URL_LOGIN' => $arBlock['SHORTEN_URL_LOGIN'],
                                    'SHORTEN_URL_KEY' => $arBlock['SHORTEN_URL_KEY']
                                ),
                                $component
                            ) ?>
                        </div>
                    </div>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>