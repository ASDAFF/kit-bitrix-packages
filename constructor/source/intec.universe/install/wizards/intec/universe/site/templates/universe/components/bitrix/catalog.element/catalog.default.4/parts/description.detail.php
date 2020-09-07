<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 */

?>
<div class="catalog-element-section">
    <div class="catalog-element-section-name intec-template-part intec-template-part-title">
        <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_DESCRIPTION_DETAIL_NAME') ?>
    </div>
    <div class="catalog-element-description-detail catalog-element-section-content">
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>
</div>