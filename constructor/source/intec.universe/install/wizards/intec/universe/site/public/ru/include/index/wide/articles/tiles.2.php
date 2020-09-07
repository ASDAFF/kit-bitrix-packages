<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\Html;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var array $block
 * @var array $data
 * @var string $page
 * @var Path $path
 * @global CMain $APPLICATION
 */

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:widget",
        "articles",
        array(
            "IBLOCK_TYPE" => "#CONTENT_ARTICLES_IBLOCK_TYPE#",
            "IBLOCK_ID" => "#CONTENT_ARTICLES_IBLOCK_ID#",
            "ELEMENTS_COUNT" => "3",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "HEADER_SHOW" => "Y",
            "HEADER_CENTER" => "N",
            "HEADER" => "Статьи",
            "DESCRIPTION_SHOW" => "Y",
            "DESCRIPTION_CENTER" => "N",
            "DESCRIPTION" => "В нашем каталоге представлены последние линейки спецтехники, систем Закажите консультацию по любому товару у наших специалистов или соберите свой заказ прямо на сайте. Мы подготовим для вас индивидуальное коммерческое предложение и вышлем персональный блок бонусов и скидок.",
            "BIG_FIRST_BLOCK" => "Y",
            "HEADER_ELEMENT_SHOW" => "Y",
            "DESCRIPTION_ELEMENT_SHOW" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600000
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>