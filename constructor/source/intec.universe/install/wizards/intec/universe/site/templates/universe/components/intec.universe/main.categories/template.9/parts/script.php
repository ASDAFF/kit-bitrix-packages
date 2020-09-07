<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\FileHelper;
use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 */

$arOptions = [
    'items' => 1,
    'loop' => false,
    'nav' => true,
    'navText' => [
        FileHelper::getFileData(__DIR__.'/../svg/slider.arrow.left.svg'),
        FileHelper::getFileData(__DIR__.'/../svg/slider.arrow.right.svg')
    ],
    'dots' => false,
    'autoHeight' => true
];

?>

<script type="text/javascript">
    (function () {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var slider = $('[data-role="slider"]', root);

        slider.owlCarousel(<?= JavaScript::toObject($arOptions) ?>);
    })();
</script>
