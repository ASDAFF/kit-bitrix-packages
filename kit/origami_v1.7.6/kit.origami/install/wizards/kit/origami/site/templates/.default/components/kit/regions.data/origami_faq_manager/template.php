<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<div class="questions__manager">
    <div class="questions__manager_mobile-wrapper">
        <div class="questions__manager_image">
            <img class="questions__manager_photo" src="<?= $arResult['FIELDS']['MANAGER_FIELDS']['PHOTO']; ?>" alt="">
        </div>
        <div class="mobile-questions__manager_name-wrapper">
            <div class="questions__manager_profession">
                <span><?= $arResult['FIELDS']['MANAGER_FIELDS']['WORK_POSITION']; ?></span></div>
            <div class="questions__manager_name"><span><?= $arResult['FIELDS']['MANAGER_FIELDS']['NAME']; ?></span>
            </div>
        </div>
    </div>
    <div class="questions__manager_info">
        <div class="questions__manager_name-wrapper">
            <div class="questions__manager_name"><span><?= $arResult['FIELDS']['MANAGER_FIELDS']['NAME']; ?></span>
            </div>
            <div class="questions__manager_profession">
                <span><?= $arResult['FIELDS']['MANAGER_FIELDS']['WORK_POSITION'] ?></span></div>
        </div>
        <div class="questions__manager_message">
            <span><?= $arResult['FIELDS']['MANAGER_FIELDS']['WORK_PROFILE'] ?></span></div>
        <button class="questions__manager_button"><span><?= GetMessage('ASK_QUESTION'); ?></span></button>
    </div>
</div>
