<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$sPublishedDate = CIBlockFormatProperties::DateFormat(
    'Y-m-d',
    MakeTimeStamp($arResult['DATE_CREATE'], CSite::GetDateFormat())
);

$sModifiedDate = CIBlockFormatProperties::DateFormat(
    'Y-m-d',
    MakeTimeStamp($arResult['TIMESTAMP_X'], CSite::GetDateFormat())
);

$sSiteUrl = (CMain::IsHTTPS()) ? "https://" : "http://";
$sSiteUrl .= $_SERVER["HTTP_HOST"];

if (!empty($arResult['DETAIL_PICTURE'])) {
    $sPicture = $arResult['DETAIL_PICTURE']['SRC'];
} else if (!empty($arResult['PREVIEW_PICTURE'])) {
    $sPicture = $arResult['PREVIEW_PICTURE']['SRC'];
} else {
    $sPicture = SITE_TEMPLATE_PATH . '/images/picture.missing.png';
}

$sPicture = $sSiteUrl.$sPicture;

?>
<div itemscope itemtype="http://schema.org/<?= $arVisual['MICRODATA']['TYPE'] ?>">
    <meta itemprop="headline name" content="<?= $arResult['NAME'] ?>">
    <link itemprop="image" href="<?= $sPicture ?>">
    <meta itemprop="datePublished" content="<?= $sPublishedDate ?>">
    <meta itemprop="dateModified" content="<?= $sModifiedDate ?>">
    <?php if (!empty($arVisual['MICRODATA']['AUTHOR'])) {?>
        <div itemprop="author" itemscope="" itemtype="http://schema.org/Person">
            <meta itemprop="name" content="<?= $arVisual['MICRODATA']['AUTHOR'] ?>">
        </div>
    <?php } ?>
    <?php if (!empty($arVisual['MICRODATA']['PUBLISHER'])) {?>
        <div itemprop="author" itemscope="" itemtype="http://schema.org/Publisher">
            <meta itemprop="name" content="<?= $arVisual['MICRODATA']['PUBLISHER'] ?>">
        </div>
    <?php } ?>
    <?php if (!empty($arResult['PREVIEW_TEXT'])) { ?>
        <meta itemprop="description" content="<?= strip_tags($arResult['PREVIEW_TEXT']) ?>" />
    <?php } ?>
    <?php if (!empty($arResult['DETAIL_TEXT'])) { ?>
        <meta itemprop="articleBody" content="<?= strip_tags($arResult['DETAIL_TEXT']) ?>" />
    <?php } ?>
</div>