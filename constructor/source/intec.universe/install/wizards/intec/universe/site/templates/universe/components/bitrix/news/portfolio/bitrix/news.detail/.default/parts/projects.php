<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arData
 */

$arFilter = [
    'ID' => $arData['PROJECTS']['VALUE']
]

?>
<div class="news-detail-other-projects">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <div class="news-detail-other-projects-name intec-template-part intec-template-part-title" data-align="center">
                <?= $arData['PROJECTS']['NAME'] ?>
            </div>
        </div>
    </div>
    <div class="news-detail-other-projects-value">
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.projects',
            'template.2',
            array(
                'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'FILTER' => $arFilter,
                'WIDE' => 'Y',
                'COLUMNS' => 4,
                'LINK_USE' => 'Y',
                'CACHE_TYPE' => 'N',
                'SORT_BY' => 'SORT',
                'SORT_ORDER' => 'ASC'
            ),
            $component
        ) ?>
    </div>
</div>
