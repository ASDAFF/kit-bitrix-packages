<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */

$arAuthServices = $arPost = array();
if (is_array($arParams['~AUTH_SERVICES'])) {
    $arAuthServices = $arParams['~AUTH_SERVICES'];
}
if (is_array($arParams['~POST'])) {
    $arPost = $arParams['~POST'];
}

$arParams['~FOR_SPLIT'] = 'Y';
$this->setFrameMode(true);
?>

<div class="bx-auth-serv">
    <?php if ($arParams['POPUP']) {
        //only one float div per page
        if (defined('BX_SOCSERV_POPUP'))
            return;
        define('BX_SOCSERV_POPUP', true);
    ?>
        <div style="display:none;">
            <div id="bx_auth_float" class="bx-auth-float">
    <?php } ?>

    <?php if ($arParams['~CURRENT_SERVICE'] != '' && $arParams['~FOR_SPLIT'] != 'Y') { ?>
        <script type="text/javascript">
            BX.ready(function(){BxShowAuthService('<?= CUtil::JSEscape($arParams['~CURRENT_SERVICE']) ?>', '<?= $arParams['~SUFFIX'] ?>')});
        </script>
    <?php } ?>

    <?php if ($arParams['~FOR_SPLIT'] == 'Y') { ?>
        <div class="bx-auth-serv-icons">
            <?php foreach($arAuthServices as $service) {
                if ($arParams['~FOR_SPLIT'] == 'Y' && is_array($service['FORM_HTML'])) {
                    $onClickEvent = $service['FORM_HTML']['ON_CLICK'];
                } else if ($service['ONCLICK'] != '') {
                    $onClickEvent = "onclick=\"". $service['ONCLICK'] ."\"";
                } else {
                    $onClickEvent = "onclick=\"BxShowAuthService('". $service['ID'] ."', '". $arParams['SUFFIX'] ."')\"";
                }
                ?>
                <a title="<?= htmlspecialcharsbx($service['NAME']) ?>"
                   href="javascript:void(0)"
                   id="bx_auth_href_<?= $arParams['SUFFIX'] . $service['ID'] ?>"
                   <?= $onClickEvent ?>>
                    <i class="bx-auth-serv-icon <?= htmlspecialcharsbx($service['ICON']) ?>"></i>
                </a>
            <?php } ?>
        </div>
    <?php } ?>
        <div class="bx-auth social">
            <form method="post"
                  name="bx_auth_services<?= $arParams['SUFFIX'] ?>"
                  target="_top"
                  action="<?= $arParams['AUTH_URL'] ?>">
                <?php if ($arParams['~FOR_SPLIT'] != 'Y') { ?>
                    <ul class="intec-ui-mod-simple">
                        <?php foreach($arAuthServices as $service) { ?>
                            <li>
                                <a href="javascript:void(0)"
                                   class="social-eshop"
                                   onclick="BxShowAuthService('<?= $service['ID'] ?>', '<?= $arParams['SUFFIX'] ?>')"
                                   id="bx_auth_href_<?= $arParams['SUFFIX'] . $service['ID'] ?>">
                                    <span class="<?= htmlspecialcharsbx($service['ICON']) ?>"></span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
                <div class="bx-auth-service-form" id="bx_auth_serv<?= $arParams['SUFFIX'] ?>" style="display:none;">
                    <?php foreach($arAuthServices as $service) {
                        if ($arParams['~FOR_SPLIT'] != 'Y' || !is_array($service['FORM_HTML'])) {
                            ?>
                            <div id="bx_auth_serv_<?= $arParams['SUFFIX'] . $service['ID']?>"
                                 style="display:none;"><?= $service['FORM_HTML'] ?></div>
                        <?php }
                    } ?>
                </div>
                <?php foreach($arPost as $key => $value) { ?>
                    <?php if (!preg_match('|OPENID_IDENTITY|', $key)) { ?>
                        <input type="hidden" name="<?= $key ?>" value="<?= $value ?>" />
                    <?php } ?>
                <?php } ?>
                <input type="hidden" name="auth_service_id" value="" />
            </form>
        </div>

    <?php if ($arParams['POPUP']) { ?>
            </div>
        </div>
    <?php } ?>
</div>

<script type="text/javascript">
    $('.bx-auth-serv .bx-auth-service-form input[type=submit]').addClass('intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-size-2 intec-ui-scheme-current');
</script>
