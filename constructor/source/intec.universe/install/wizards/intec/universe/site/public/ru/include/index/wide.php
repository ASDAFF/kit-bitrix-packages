<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;
use intec\core\collections\Arrays;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var string $page
 * @var Closure $render($block, $data = [])
 * @var Path $path
 * @global CMain $APPLICATION
 */

$render($blocks->get('icons'));
$render($blocks->get('sections'));
$render($blocks->get('categories'));
$render($blocks->get('gallery'));
$render($blocks->get('products'));
$render($blocks->get('shares'));
?>
<?= Html::beginTag('div') ?>
    <?php $APPLICATION->IncludeComponent(
        "intec.universe:main.form",
        "template.1",
        array(
            "ID" => "#FORMS_FEEDBACK_ID#",
            "NAME" => "Обратная связь",
            "SETTINGS_USE" => "Y",
            "LAZYLOAD_USE" => "N",
            "CONSENT" => "#SITE_DIR#company/consent/",
            "TEMPLATE" => ".default",
            "TITLE" => "Индивидуальный подход",
            "DESCRIPTION_SHOW" => "Y",
            "DESCRIPTION_TEXT" => "Наши специалисты свяжутся с вами и найдут оптимальные для вас условия сотрудничества",
            "BUTTON_TEXT" => "Обратная связь",
            "THEME" => "dark",
            "VIEW" => "left",
            "BACKGROUND_COLOR" => "#f4f4f4",
            "BACKGROUND_IMAGE_USE" => "Y",
            "BACKGROUND_IMAGE_PATH" => "#SITE_DIR#images/forms/form.1/background.jpg",
            "BACKGROUND_IMAGE_HORIZONTAL" => "center",
            "BACKGROUND_IMAGE_VERTICAL" => "center",
            "BACKGROUND_IMAGE_SIZE" => "cover",
            "BACKGROUND_IMAGE_PARALLAX_USE" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600000"
        ),
        false
    ); ?>
<?= Html::endTag('div') ?>
<?php
$render($blocks->get('services'));
$render($blocks->get('stages'));
$render($blocks->get('advantages'));
$render($blocks->get('video'));
$render($blocks->get('projects'));
$render($blocks->get('rates'));
$render($blocks->get('staff'));
$render($blocks->get('certificates'));
$render($blocks->get('faq'));
$render($blocks->get('videos'));
$render($blocks->get('news'));
$render($blocks->get('reviews'));
$render($blocks->get('articles'));
$render($blocks->get('about'));
$render($blocks->get('brands'));
$render($blocks->get('contacts'));

?>
