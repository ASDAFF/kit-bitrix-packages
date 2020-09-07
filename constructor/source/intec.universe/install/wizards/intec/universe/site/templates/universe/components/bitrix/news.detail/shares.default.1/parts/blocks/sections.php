<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="news-detail-sections widget">
    <div class="news-detail-sections-wrapper intec-content intec-content-visible">
        <div class="news-detail-sections-wrapper-2 intec-content-wrapper">
            <?php if (!empty($arBlock['HEADER']['VALUE'])) { ?>
                <div class="news-detail-sections-header widget-header">
                    <?= Html::tag('div', $arBlock['HEADER']['VALUE'], [
                        'class' => [
                            'widget-title',
                            'align-'.$arBlock['HEADER']['POSITION']
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <div class="news-detail-sections-content widget-content">
                <?php $APPLICATION->IncludeComponent(
                    'intec.universe:main.sections',
                    'template.1',
                    array(
                        'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
                        'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
                        'SECTIONS' => $arBlock['IBLOCK']['ELEMENTS'],
                        'SETTINGS_USE' => 'N',
                        'LAZYLOAD_USE' => $arVisual['LAZYLOAD']['USE'] ? 'Y' : 'N',
                        'LINE_COUNT' => $arBlock['COLUMNS'],
                        'DEPTH' => 1,
                        'HEADER_SHOW' => 'N',
                        'DESCRIPTION_SHOW' => 'N',
                    ),
                    $component
                ) ?>
            </div>
        </div>
    </div>
</div>