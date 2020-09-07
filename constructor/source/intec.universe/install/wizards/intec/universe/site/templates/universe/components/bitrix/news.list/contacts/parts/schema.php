<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 */

if (empty($arResult['CONTACT']))
    return;

?>
<div itemscope itemtype="http://schema.org/LocalBusiness" style="display:none;">
    <?php if (!empty($arResult['CONTACT']['NAME'])) { ?>
        <span itemprop="name">
            <?= $arResult['CONTACT']['NAME'] ?>
        </span>
    <?php } ?>
    <?php if (!empty($arResult['CONTACT']['DATA']['ADDRESS'])) { ?>
        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
            <span itemprop="streetAddress">
                <?= $arResult['CONTACT']['DATA']['ADDRESS'] ?>
            </span>
        </div>
    <?php } ?>
    <?php if (!empty($arResult['CONTACT']['DATA']['PHONE'])) { ?>
        <span itemprop="telephone">
            <?= $arResult['CONTACT']['DATA']['PHONE']['DISPLAY'] ?>
        </span>
    <?php } ?>
    <?php if (!empty($arResult['CONTACT']['DATA']['EMAIL'])) { ?>
        <span itemprop="email">
            <?= $arResult['CONTACT']['DATA']['EMAIL'] ?>
        </span>
    <?php } ?>
    <?php if (!empty($arResult['CONTACT']['DATA']['OPENING_HOURS'])) { ?>
        <time itemprop="openingHours" datetime="<?= $arResult['CONTACT']['DATA']['OPENING_HOURS'] ?>"></time>
    <?php } ?>
</div>