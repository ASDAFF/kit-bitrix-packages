<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var $arVisual
 * @var $arData
 */

?>
<?php return function (&$arData) use (&$arVisual) { ?>
    <?php $iCounter = 0 ?>
    <?php foreach ($arData['ADDITIONAL'] as $arAdditional) {

        $iCounter++;

        if ($iCounter > 4)
            break;

    ?>
        <div class="widget-item-additional-item intec-grid-item intec-grid-item-a-stretch">
            <div class="widget-item-additional-item-content">
                <div class="widget-item-additional-item-name" title="<?= $arAdditional['NAME'] ?>">
                    <?= $arAdditional['NAME'] ?>
                </div>
                <div class="widget-item-additional-item-description" title="<?= $arAdditional['DESCRIPTION'] ?>">
                    <?= $arAdditional['DESCRIPTION'] ?>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>