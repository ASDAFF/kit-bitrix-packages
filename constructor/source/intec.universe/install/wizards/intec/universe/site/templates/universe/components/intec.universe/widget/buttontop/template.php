<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?php if (!defined('EDITOR')) { ?>
<div class="widget c-widget c-widget-buttontop" id="<?= $sTemplateId ?>">
    <div class="widget-button intec-cl-background intec-cl-background-light-hover" data-role="button"></div>
</div>

<script>
    var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
    var button = $('[data-role="button"]', root);

    $(window).scroll(function() {
        var windowHeight = document.documentElement.clientHeight;

        if($(this).scrollTop() > windowHeight) {
            button.fadeIn();
        }
        else {
            button.fadeOut();
        }
    });
    button.click(function() {
        $('body, html').animate({
            scrollTop: 0
        }, 600);
    });
</script>
<style>
    .c-widget-buttontop .widget-button {
        border-radius:<?=$arParams["RADIUS"]?$arParams["RADIUS"]:0;?>px;
    }
</style>
<?php } else { ?>
    <div class="constructor-element-stub">
        <div class="constructor-element-stub-wrapper">
            <?= GetMessage('WBT_TITLE') ?>
        </div>
    </div>
<?php } ?>
