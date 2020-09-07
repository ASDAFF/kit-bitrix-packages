<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arVisual
 * @var array $arData
 */

?>
<div class="news-detail-features">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?= Html::beginTag('div', [
                'class' => 'news-detail-features-items',
                'data-narrow' => $arVisual['FEATURES']['NARROW'] ? 'true' : 'false'
            ]) ?>
                <?php foreach ($arData['FEATURES'] as $arFeature) { ?>
                    <div class="news-detail-features-item">
                        <div class="news-detail-features-item-name intec-template-part intec-template-part-title" data-align="center">
                            <?= $arFeature['NAME'] ?>
                        </div>
                        <div class="news-detail-features-item-value">
                            <?= $arFeature['VALUE'] ?>
                        </div>
                    </div>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>
