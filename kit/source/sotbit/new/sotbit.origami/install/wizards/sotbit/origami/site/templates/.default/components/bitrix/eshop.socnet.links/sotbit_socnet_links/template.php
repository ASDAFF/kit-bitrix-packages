<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if (is_array($arResult["SOCSERV"]) && !empty($arResult["SOCSERV"])) {
    ?>
    <div class="footer-block__social_link">
        <?
        foreach ($arResult["SOCSERV"] as $socserv):?>
        <div class="footer-block__wrapper <?= htmlspecialcharsbx($socserv["CLASS"]) ?>-wrapper">
            <a class="footer-block__social_link_name" href="<?= htmlspecialcharsbx($socserv["LINK"]) ?>"
               target="_blank">
                <svg class="svg-social-icons social-icon-<?= htmlspecialcharsbx($socserv["CLASS"]) ?>" width="28"
                     height="28">
                    <use
                        xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#<?= htmlspecialcharsbx($socserv["CLASS"]) ?>"></use>
                </svg>
            </a>
        </div>
        <?endforeach ?>
    </div>
    <?
}
?>
