<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
$arData = $arResult['DATA'];

?>
<div class="ns-bitrix c-subscribe-edit c-subscribe-edit-blog-1" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?php if ($arVisual['HEADER']['SHOW']) { ?>
                <div class="subscribe-edit-header" data-align="<?= $arVisual['HEADER']['POSITION'] ?>">
                    <?= $arVisual['HEADER']['TEXT'] ?>
                </div>
            <?php } ?>
            <div class="subscribe-edit-body">
                <?php if ($arData['ACCESS']) { ?>
                    <form action="<?= $arResult['FORM_ACTION'] ?>" method="post">
                        <?= bitrix_sessid_post() ?>
                        <?= Html::hiddenInput('PostAction', $arData['ACTION']) ?>
                        <?= Html::hiddenInput('ID', $arData['ID']) ?>
                        <?= Html::hiddenInput('RUB_ID[]', 0) ?>
                        <?php if (!empty($arResult['MESSAGE']) || !empty($arResult['ERROR'])) {
                            include(__DIR__.'/parts/notes.php');
                        } ?>
                        <?php if (!$arData['SUBSCRIBED'] && !$arData['CONFIRMED']) {
                            include(__DIR__.'/parts/subscribe.php');
                        } else if ($arData['SUBSCRIBED'] && !$arData['CONFIRMED']) {
                            include(__DIR__.'/parts/confirm.php');
                        } else if ($arData['SUBSCRIBED'] && $arData['CONFIRMED']) {
                            include(__DIR__.'/parts/edit.php');
                        } ?>
                    </form>
                <?php } else { ?>
                    <div>
                        <?php include(__DIR__.'/parts/notes.php') ?>
                        <?php if ($arVisual['AUTHORISATION']['SHOW']) {
                            include(__DIR__.'/parts/authorisation.php');
                        } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>