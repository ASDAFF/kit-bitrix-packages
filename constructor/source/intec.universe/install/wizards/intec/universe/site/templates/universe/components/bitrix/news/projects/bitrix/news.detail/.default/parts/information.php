<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

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
 * @var string $sTemplateId
 */

if (empty($arResult['DETAIL_TEXT']))
    return;

?>
<div class="project-section project-section-information">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="project-section-title intec-ui-markup-header">
                <?= Loc::getMessage('N_PROJECTS_N_D_DEFAULT_SECTION_INFORMATION') ?>
            </div>
            <div class="project-information intec-ui-markup-text">
                <?= $arResult['DETAIL_TEXT'] ?>
            </div>
        </div>
    </div>
</div>
