<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

?>
<div class="ns-bitrix c-catalog-element c-catalog-element-services-intec" id="<?= $sTemplateId ?>">
    <?php
        include(__DIR__.'/parts/blocks/banner.php');
        include(__DIR__.'/parts/blocks/additional.text.php');
        include(__DIR__.'/parts/tabs/tabs.php');
        include(__DIR__.'/parts/blocks/projects.php');
        include(__DIR__.'/parts/blocks/reviews.php');
        include(__DIR__.'/parts/blocks/advantages.php');
        include(__DIR__.'/parts/blocks/faq.php');
        include(__DIR__.'/parts/blocks/recommend.php');
        include(__DIR__.'/parts/blocks/video.php');
    ?>
</div>
<?php include(__DIR__.'/parts/script.php') ?>