<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var string $sVariable
 * @var array $arValues
 * @var array $arYears
 * @var CAllMain $APPLICATION
 */

?>
<div class="news-panel-1">
    <div class="news-panel-1-items intec-grid intec-grid-wrap">
        <div class="news-panel-1-item intec-grid-item-auto">
            <?= Html::tag(empty($arValues[$sVariable]) ? 'span' : 'a', Loc::getMessage('C_NEWS_NEWS_1_TEMPLATE_PANEL_RESET'), [
                'class' => Html::cssClassFromArray([
                    'intec-cl-text' => empty($arValues[$sVariable]),
                    'intec-cl-text-hover' => !empty($arValues[$sVariable])
                ], true),
                'href' => !empty($arValues[$sVariable]) ? $APPLICATION->GetCurPageParam('', [$sVariable]) : null
            ]) ?>
        </div>
        <?php foreach ($arYears as $sYear) {

            $bActive = false;

            if (!empty($arValues[$sVariable]) && $arValues[$sVariable] == $sYear)
                $bActive = true;

        ?>
            <div class="news-panel-1-item intec-grid-item-auto">
                <?= Html::tag($bActive ? 'span' : 'a', $sYear, [
                    'class' => Html::cssClassFromArray([
                        'intec-cl-text' => $bActive,
                        'intec-cl-text-hover' => !$bActive
                    ], true),
                    'href' => !$bActive ? $APPLICATION->GetCurPageParam($sVariable.'='.$sYear, [$sVariable]) : null
                ]) ?>
            </div>
        <?php } ?>
        <?php unset($sYear, $bActive) ?>
    </div>
</div>
