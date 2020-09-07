<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var array $arBlock
 */

?>
<div class="news-detail-description">
    <div class="news-detail-description-wrapper intec-content">
        <div class="news-detail-description-wrapper-2 intec-content-wrapper">
            <div class="news-detail-description-content">
                <?php if (!empty($arBlock['DURATION'])) { ?>
                    <div class="news-detail-description-duration">
                        <?= $arBlock['DURATION'] ?>
                    </div>
                <?php } ?>
                <?php if (!empty($arBlock['TEXT'])) { ?>
                    <div class="news-detail-description-text intec-ui-markup-text">
                        <?= $arBlock['TEXT'] ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
