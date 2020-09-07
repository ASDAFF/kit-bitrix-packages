<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 */

?>
<div class="catalog-element-article" data-role="article" data-show="<?= !empty($arResult['ARTICLE']) ? 'true' : 'false' ?>">
    <span class="catalog-element-article-name">
        <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_ARTICLE').':' ?>
    </span>
    <span class="catalog-element-article-value" data-role="article.value">
        <?= $arResult['ARTICLE'] ?>
    </span>
</div>