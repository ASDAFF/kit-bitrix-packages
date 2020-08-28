<?

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss(SITE_DIR . "include/sotbit_origami/files/btn_error-share/style.css");
Asset::getInstance()->addJs(SITE_DIR . "include/sotbit_origami/files/btn_error-share/script.js");
?>

<div class="btn_error-share__overlays-wrapper">
    <div class="btn_error-share">
        <div class="btn_error-share__wrapper">
            <div class="btn_error-share__content">
                <div class="btn_error-share__content-icons-wrapper">
                    <div class="btn_error-share__icon-wrapper">
                        <svg class="icon_share" width="22" height="24">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_share"></use>
                        </svg>
                    </div>
                    <div class="btn_error-share__icon-wrapper">
                        <svg class="icon_share" width="24" height="22">
                            <use
                                xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mistake_big"></use>
                        </svg>
                    </div>
                </div>
                <div class="btn_error-share__content-icon-close">
                    <svg class="icon_close" width="17" height="17">
                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel_bold"></use>
                    </svg>
                </div>
            </div>
            <div class="btn_error-share__background-opacity">
            </div>
        </div>

        <div class="btn_error-share__share" onclick="callSubscribePopup('<?= SITE_DIR ?>', '<?= SITE_ID ?>' , this)" data-address="<?=$APPLICATION->GetCurPage();?>">
            <svg class="icon_share" width="16" height="18">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_share"></use>ё
            </svg>
        </div>

        <div class="btn_error-share__error" onclick="foundError('<?= SITE_DIR ?>', '<?= SITE_ID ?>', this)"
             title="Нашли ошибку?">
            <svg class="icon_mistake_big" width="18" height="16">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mistake_big"></use>
            </svg>
        </div>
        <script>
            (function () {
                initBtnErrorShare();
            })();
        </script>
    </div>
    <div class="btn_error-share__overlay overlay-black"></div>
    <div class="btn_error-share__overlay overlay-white"></div>
</div>
