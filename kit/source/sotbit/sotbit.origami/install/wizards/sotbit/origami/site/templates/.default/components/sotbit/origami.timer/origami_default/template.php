<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;

$this->addExternalCss(SITE_TEMPLATE_PATH . "/assets/plugin/timer/style.min.css");
$this->addExternalJS(SITE_TEMPLATE_PATH . "/assets/plugin/timer/script.js");
//Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/plugin/timer/script.js");
//Asset::getInstance()->addCss();
$days = '';
if ($arParams['TIMER_SIZE'] == 'lg') {
    $days = GetMessage('TIMER_DAYS');
} else {
    $days = GetMessage('TIMER_DAYS_MB');
}

?>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        let timerID_<?= $arParams['ID'] ?> = new TimerAction({
            itemParent: '[data-timer="timerID_<?= $arParams['BLOCK_ID'] ?>"]',
            endTime: (<?=MakeTimeStamp($arParams['TIMER_DATE_END'], "DD.MM.YYYY HH:MI:SS")?>) * 1000,
            day: "<?=$days?>",
            hours: "<?=GetMessage('TIMER_HOURS')?>",
            minutes: "<?=GetMessage('TIMER_MINUTES')?>",
            seconds: "<?=GetMessage('TIMER_SECONDS')?>",
            size: "<?=$arParams['TIMER_SIZE']?>",
        });

        <?if($arParams['MOBILE_DESTROY'] && $arParams['MOBILE_DESTROY'] == 'Y'):?>
            function toggleTimer(isShow, idTimer) {
                if (isShow && !idTimer.isShow) {
                    idTimer.timerInit();
                }
                if (!isShow && idTimer.isShow) {
                    idTimer.timerDestroy();
                }
            }

            if (window.innerWidth < 768) {
                toggleTimer (false, timerID_<?= $arParams['ID'] ?>);
            }

            window.addEventListener('resize', function () {
                if (window.innerWidth < 768) {
                    toggleTimer (false, timerID_<?= $arParams['ID'] ?>);
                } else {
                    toggleTimer (true, timerID_<?= $arParams['ID'] ?>);
                }
            });
        <?endif;?>
    });
</script>



