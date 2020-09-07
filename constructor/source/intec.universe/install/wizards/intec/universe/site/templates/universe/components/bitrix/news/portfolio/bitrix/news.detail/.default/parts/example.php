<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arData
 */

?>
<div class="news-detail-example">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <?php $bExampleFirst = true ?>
            <?php foreach ($arData['EXAMPLE'] as $arExample) { ?>
                <?php if (!empty($arExample['VALUE'])) { ?>
                    <div class="news-detail-example-item">
                        <div class="news-detail-example-item-name intec-template-part intec-template-part-title" data-align="center">
                            <?= $arExample['NAME'] ?>
                        </div>
                        <div class="news-detail-example-item-value">
                            <?php if ($arExample['FILE']) { ?>
                                <?php foreach ($arExample['VALUE'] as $arValue) { ?>
                                    <div class="news-detail-example-item-value-item">
                                        <?= Html::img($arValue['VALUE'], [
                                            'alt' => !empty($arValue['DESCRIPTION']) ? $arValue['DESCRIPTION'] : $arResult['NAME'],
                                            'title' => !empty($arValue['DESCRIPTION']) ? $arValue['DESCRIPTION'] : $arResult['NAME'],
                                            'data-shadow' => $arVisual['EXAMPLE']['SHADOW'] && $bExampleFirst ? 'true' : 'false',
                                            'loading' => 'lazy'
                                        ]) ?>
                                        <?php if (!empty($arValue['DESCRIPTION'])) { ?>
                                            <div class="news-detail-example-item-value-item-description" data-align="'center">
                                                <?= $arValue['DESCRIPTION'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="news-detail-example-item-value-item">
                                    <?php if (Type::isArray($arExample['VALUE'])) { ?>
                                        <?= implode(', ', $arExample['VALUE']) ?>
                                    <?php } else { ?>
                                        <?= $arExample['VALUE'] ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php $bExampleFirst = false ?>
            <?php } ?>
        </div>
    </div>
</div>