<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/** @var array $arParams
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

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
?>
<div class="ns-bitrix c-news-list c-news-list-vacancies-2" id="<?= $sTemplateId ?>">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <div class="news-list-items" data-role="items">
                <?php foreach($arResult['ITEMS'] as $arItem) { ?>
                <?php
                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $arData = $arItem['DATA'];

                    $sSalary = ArrayHelper::getValue($arData, ['SALARY', 'VALUE'])
                ?>
                    <div class="news-list-item" data-role="item" id="<?= $sAreaId ?>">
                        <div class="news-list-item-name" data-role="item.head" data-active="false">
                            <div class="intec-grid intec-grid-wrap intec-grid-i-h-10 intec-grid-a-v-center">
                                <div class="intec-grid-item intec-grid-item-550-1 news-list-item-name-text">
                                    <?= $arItem['NAME'] ?>
                                </div>
                                <div class="intec-grid-item-auto intec-grid-item-550-1">
                                    <div class="intec-grid intec-grid-nowrap intec-grid-a-v-center">
                                        <?php if ($arVisual['SALARY']['SHOW']) { ?>
                                        <div class="intec-grid-item news-list-item-salary">
                                            <?=
                                            number_format($sSalary, 0, '.', ' ').
                                            (!empty($arParams['CURRENCY']) ? ' '.$arParams['CURRENCY'] : '')
                                            ?>
                                        </div>
                                        <?php } ?>
                                        <div class="intec-grid-item-auto news-list-item-indicators">
                                            <div class="intec-aligner"></div>
                                            <i class="fa fa-chevron-up news-list-item-indicator news-list-item-indicator-active"></i>
                                            <i class="fa fa-chevron-down news-list-item-indicator news-list-item-indicator-inactive"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="news-list-item-description" data-role="item.body">
                            <div class="news-list-item-description-wrapper">
                                <div class="intec-grid intec-grid-wrap intec-grid-i-25 intec-grid-a-v-stretch">
                                    <div class="intec-grid-item intec-grid-item-768-1 news-list-item-description-text">
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    </div>
                                    <?php if ($arVisual['FORM']['SHOW'] == 'Y' && !empty($arParams['FORM_SUMMARY_ID'])) { ?>
                                        <div class="intec-grid-item-auto intec-grid-item-768-1 news-list-item-description-buttons">
                                            <div class="news-list-item-description-button intec-ui intec-ui-control-button intec-ui-scheme-current" data-name="<?= Html::encode($arItem['NAME']) ?>" data-action="form"><?= GetMessage('C_NEWS_LIST_VACANCIES_2_FORM_SUMMARY') ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>