<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arData
 */

$iCounter = 0

?>
<div class="news-detail-tags">
    <div class="intec-grid intec-grid-wrap intec-grid-i-5">
        <?php foreach ($arData['TAGS'] as $sTag) {

            if ($iCounter >= 5)
                $iCounter = 0;

            $iCounter++;

        ?>
            <div class="intec-grid-item-auto">
                <div class="news-detail-tags-item" data-color="<?= $iCounter ?>">
                    <?= '#'.$sTag ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php unset($iCounter, $sTag) ?>