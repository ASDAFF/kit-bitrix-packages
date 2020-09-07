<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

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

if (empty($arResult['OBJECTIVE']))
    return;

?>
<div class="project-section project-section-objective">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="project-section-title intec-ui-markup-header">
                <?= Loc::getMessage('N_PROJECTS_N_D_DEFAULT_SECTION_OBJECTIVE') ?>
            </div>
            <div class="project-objective intec-ui-markup-text">
                <?= $arResult['OBJECTIVE'] ?>
            </div>
        </div>
    </div>
</div>