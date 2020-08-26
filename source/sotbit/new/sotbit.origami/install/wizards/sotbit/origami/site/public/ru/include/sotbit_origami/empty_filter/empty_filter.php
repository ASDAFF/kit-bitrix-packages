<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss(SITE_DIR . "include/sotbit_origami/empty_filter/style.css");

?>
<div class="empty-filter">
    <div class="empty-filter__icon-wrapper">
        <svg class="empty-filter__icon" width="120" height="110">
            <use
                xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_filter_empty"></use>
        </svg>
    </div>
    <div class="empty-filter__text-block">
        <div class="empty-filter__text-block-title">
            <?= Loc::getMessage("NO_RESULT_TITLE") ?>
        </div>
        <div class="empty-filter__text-block-subtitle">
            <?= Loc::getMessage("TRY_ANOTHER_REQUEST") ?><a class="main-color_link" href="/catalog/"><?= Loc::getMessage("LINK_TEXT") ?></a>
        </div>
    </div>
</div>
