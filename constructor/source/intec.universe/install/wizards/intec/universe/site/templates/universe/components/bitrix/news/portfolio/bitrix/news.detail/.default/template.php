<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
$arData = $arResult['DATA'];

?>
<div class="ns-bitrix c-news-detail c-news-detail-default" id="<?= $sTemplateId ?>">
    <?php if ($arVisual['BANNER']['SHOW']) {
        include(__DIR__.'/parts/banner.php');
    } ?>
    <?php if ($arVisual['CHARACTERISTICS']['SHOW'] || $arVisual['INFORMATION']['SHOW']) { ?>
        <div class="news-detail-characteristics-block" data-background="true">
            <div class="intec-content">
                <div class="intec-content-wrapper">
                    <?php if ($arVisual['CHARACTERISTICS']['SHOW']) {
                        include(__DIR__.'/parts/characteristics.php');
                    } ?>
                    <?php if ($arVisual['CHARACTERISTICS']['SHOW'] && $arVisual['INFORMATION']['SHOW']) { ?>
                        <div class="news-detail-characteristics-delimiter"></div>
                    <?php } ?>
                    <?php if ($arVisual['INFORMATION']['SHOW']) {
                        include(__DIR__.'/parts/information.php');
                    } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($arVisual['FEATURES']['SHOW']) {
        include(__DIR__.'/parts/features.php');
    } ?>
    <?php if ($arVisual['EXAMPLE']['SHOW']) {
        include(__DIR__.'/parts/example.php');
    } ?>
    <?php if ($arVisual['RESULT']['SHOW']) {
        include(__DIR__.'/parts/result.php');
    } ?>
    <?php if ($arVisual['REVIEW']['SHOW']) {
        include(__DIR__.'/parts/review.php');
    } ?>
    <?php if ($arVisual['SERVICES']['SHOW']) {
        include(__DIR__.'/parts/services.php');
    } ?>
    <?php if ($arVisual['PROJECTS']['SHOW']) {
        include(__DIR__ . '/parts/projects.php');
    } ?>
</div>