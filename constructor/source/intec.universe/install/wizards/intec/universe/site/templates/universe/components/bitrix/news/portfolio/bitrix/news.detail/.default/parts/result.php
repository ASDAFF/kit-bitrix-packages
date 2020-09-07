<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arData
 */

?>
<?= Html::beginTag('div', [
    'class' => 'news-detail-result',
    'data-background' => $arVisual['RESULT']['BACKGROUND'] ? 'true' : 'false'
]) ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?= Html::beginTag('div', [
                'class' => 'news-detail-result-wrapper',
                'data-narrow' => $arVisual['RESULT']['NARROW'] ? 'true' : 'false'
            ]) ?>
                <div class="news-detail-result-name intec-template-part intec-template-part-title" data-align="center">
                    <?= $arData['RESULT']['NAME'] ?>
                </div>
                <div class="news-detail-result-value intec-grid intec-grid-a-v-center">
                    <div class="news-detail-result-value-decoration-wrap intec-grid-item-auto">
                        <div class="news-detail-result-value-decoration">
                            <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 5L5 9L13 1" stroke="#2fc84f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="news-detail-result-value-text-wrap intec-grid-item">
                        <div class="news-detail-result-value-text">
                            <?= $arData['RESULT']['VALUE'] ?>
                        </div>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        </div>
    </div>
<?= Html::endTag('div') ?>