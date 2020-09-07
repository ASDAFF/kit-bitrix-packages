<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arData
 */

$arFilter = [
    'ID' => $arData['SERVICES']['VALUE']
]

?>
<div class="news-detail-performed-sevices">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <div class="news-detail-performed-sevices-name intec-template-part intec-template-part-title" data-align="center">
                <?= $arData['SERVICES']['NAME'] ?>
            </div>
            <div class="news-detail-performed-sevices-value">
                <?php $APPLICATION->IncludeComponent(
                    "intec.universe:main.categories",
                    "template.14",
                    array(
                        'IBLOCK_TYPE' => $arParams['SERVICES_IBLOCK_TYPE'],
                        'IBLOCK_ID' => $arParams['SERVICES_IBLOCK_ID'],
                        'FILTER' => $arFilter,
                        'LINK_MODE' => 'property',
                        'PROPERTY_LINK' => $arParams['SERVICES_PROPERTY_LINK'],
                        'HEADER_SHOW' => 'N',
                        'DESCRIPTION_SHOW' => 'N',
                        'COLUMNS' => '2',
                        'PICTURE_SHOW' => 'Y',
                        'PREVIEW_SHOW' => 'Y',
                        'LINK_USE' => 'Y',
                        'LINK_BLANK' => 'Y',
                        'CACHE_TYPE' => 'N',
                        'SORT_BY' => 'SORT',
                        'SORT_ORDER' => 'ASC'
                    ),
                    $component
                ) ?>
            </div>
        </div>
    </div>
</div>