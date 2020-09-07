<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @global CMain $APPLICATION
 */

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('intec.core'))
    return;

$rights = $APPLICATION->GetGroupRight($sModule);

if ($rights < 'R')
    return;

$request = Core::$app->request;
$parameters = Core::$app->parameters;
$sites = Arrays::fromDBResult(CSite::GetList($by = 'sort', $order = 'asc'))
    ->indexBy('ID');

if ($request->getIsPost() && check_bitrix_sessid()) {
    $reset = $request->post('reset');
    $reset = !empty($reset);
    $sitesData = $request->post('sites');

    foreach ($sites as $site) {
        $siteData = ArrayHelper::getValue($sitesData, $site['ID']);

        if ($reset)
            $siteData = [
                'minimizationUse' => false,
                'minimizationSpaces' => false,
                'minimizationTags' => false,
                'minimizationCommentaries' => false,
                'minimizationContent' => false
            ];

        if (Type::isArray($siteData))
            foreach ($siteData as $key => $value)
                if ($parameters->canSetProperty($key, false, false))
                    $parameters->{'set'.$key}($value, $site['ID']);
    }
}

$tabs = [];

foreach ($sites as $site) {
    $tabs[] = [
        'DIV' => $site['ID'],
        'TAB' => Loc::getMessage('intec.core.options.tabs.tab.title', [
            '#id#' => $site['ID'],
            '#name#' => $site['NAME']
        ]),
        'ICON' => 'settings',
        'TITLE' => Loc::getMessage('intec.core.options.tabs.tab.title', [
            '#id#' => $site['ID'],
            '#name#' => $site['NAME']
        ])
    ];
}

$control = new CAdminTabControl('tabs', $tabs);

?>
<form method="POST">
    <?= bitrix_sessid_post() ?>
    <?php $control->Begin() ?>
    <?php foreach ($sites as $site) { ?>
        <?php $control->BeginNextTab() ?>
        <tr>
            <td class="adm-detail-content-cell-l" width="40%">
                <?= Loc::getMessage('intec.core.options.parameters.minimizationUse') ?>:
            </td>
            <td class="adm-detail-content-cell-r" width="60%">
                <?= Html::hiddenInput('sites['.$site['ID'].'][minimizationUse]', 0) ?>
                <?= Html::checkbox('sites['.$site['ID'].'][minimizationUse]', $parameters->getMinimizationUse($site['ID']), [
                    'value' => 1
                ]) ?>
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l" width="40%">
                <?= Loc::getMessage('intec.core.options.parameters.minimizationSpaces') ?>:
            </td>
            <td class="adm-detail-content-cell-r" width="60%">
                <?= Html::hiddenInput('sites['.$site['ID'].'][minimizationSpaces]', 0) ?>
                <?= Html::checkbox('sites['.$site['ID'].'][minimizationSpaces]', $parameters->getMinimizationSpaces($site['ID']), [
                    'value' => 1
                ]) ?>
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l" width="40%">
                <?= Loc::getMessage('intec.core.options.parameters.minimizationTags') ?>:
            </td>
            <td class="adm-detail-content-cell-r" width="60%">
                <?= Html::hiddenInput('sites['.$site['ID'].'][minimizationTags]', 0) ?>
                <?= Html::checkbox('sites['.$site['ID'].'][minimizationTags]', $parameters->getMinimizationTags($site['ID']), [
                    'value' => 1
                ]) ?>
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l" width="40%">
                <?= Loc::getMessage('intec.core.options.parameters.minimizationCommentaries') ?>:
            </td>
            <td class="adm-detail-content-cell-r" width="60%">
                <?= Html::hiddenInput('sites['.$site['ID'].'][minimizationCommentaries]', 0) ?>
                <?= Html::checkbox('sites['.$site['ID'].'][minimizationCommentaries]', $parameters->getMinimizationCommentaries($site['ID']), [
                    'value' => 1
                ]) ?>
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l" width="40%">
                <?= Loc::getMessage('intec.core.options.parameters.minimizationContent') ?>:
            </td>
            <td class="adm-detail-content-cell-r" width="60%">
                <?= Html::hiddenInput('sites['.$site['ID'].'][minimizationContent]', 0) ?>
                <?= Html::checkbox('sites['.$site['ID'].'][minimizationContent]', $parameters->getMinimizationContent($site['ID']), [
                    'value' => 1
                ]) ?>
            </td>
        </tr>
    <?php } ?>
    <?php $control->Buttons() ?>
        <input type="submit" class="adm-btn-save" name="apply" value="<?= Loc::getMessage('intec.core.options.buttons.apply') ?>" />
        <input type="submit" name="reset" value="<?= Loc::getMessage('intec.core.options.buttons.reset') ?>" />
    <?php $control->End() ?>
</form>


<?php    /*$bApply = !empty($_REQUEST['Apply']);
    $bReset = !empty($_REQUEST['Reset']);

    $rsSites = CSite::GetList($by = 'id', $sort = 'asc', array('ACTIVE' => 'Y'));
    $arSites = [];
    $arTabs = [];

    while ($arSite = $rsSites->GetNext())
        $arSites[$arSite['ID']] = $arSite;

    if ($bIsPost && check_bitrix_sessid() && ($bApply || $bReset)) {
        $arOptions = $_REQUEST['Options'];

        foreach ($arSites as $sSiteId => $arSite)
            foreach ($arSettings as $sCode => $arSetting) {
                $sType = ArrayHelper::getValue($arSetting, 'type');
                $sValue = ArrayHelper::getValue($arOptions, [$sSiteId, $sCode]);

                if ($bReset)
                    $sValue = ArrayHelper::getValue($arSetting, 'default');

                if ($sType == 'boolean') {
                    $sValue = Type::toBoolean($sValue) ? 1 : 0;
                } if ($sType == 'integer') {
                    $sValue = Type::toInteger($sValue);
                } else if ($sType == 'float') {
                    $sValue = Type::toFloat($sValue);
                } else if ($sType == 'list') {
                    $arValues = ArrayHelper::getValue($arSetting, 'values');
                    $arValues = ArrayHelper::getKeys($arValues);

                    if (!Type::isArray($arValues))
                        $arValues = [];

                    $sValue = ArrayHelper::fromRange($arValues, $sValue);
                }

                COption::SetOptionString($sModule, $sCode, $sValue, false, $sSiteId);
            }
    }

    foreach ($arSites as $arSite) {
        $arSiteMacros = [];

        foreach ($arSite as $sKey => $sSiteField)
            $arSiteMacros['#' . $sKey . '#'] = $sSiteField;

        $arTabs[] = [
            'DIV' => $arSite['ID'],
            'TAB' => Loc::getMessage('intec.universe.options.tab', $arSiteMacros),
            'ICON' => 'settings',
            'TITLE' => Loc::getMessage('intec.universe.options.tab', $arSiteMacros)
        ];

        unset($arSiteMacros, $sKey, $sSiteField, $arSite);
    }

    $oTabControl = new CAdminTabControl('Settings', $arTabs);
?>
    <form method="POST">
        <?= bitrix_sessid_post() ?>
        <?php $oTabControl->Begin() ?>
        <?php foreach ($arSites as $sSiteId => $arSite) { ?>
        <?php
            $oTabControl->BeginNextTab();
        ?>
            <tr>
                <td>
                    <table class="adm-detail-content-table edit-table">
                        <?php foreach ($arSettings as $sCode => $arSetting) { ?>
                        <?php
                            $sKey = 'Options['.Html::encode($sSiteId).']['.Html::encode($sCode).']';
                            $sName = ArrayHelper::getValue($arSetting, 'name');
                            $sType = ArrayHelper::getValue($arSetting, 'type');
                            $sDefault = ArrayHelper::getValue($arSetting, 'default');
                            $sValue = COption::GetOptionString($sModule, $sCode, $sDefault, $sSiteId);
                            $arValues = [];

                            if ($sType == 'list') {
                                $arValues = ArrayHelper::getValue($arSetting, 'values');

                                if (!Type::isArray($arValues))
                                    $arValues = [];
                            }
                        ?>
                            <tr>
                                <td class="adm-detail-content-cell-l" style="width: 50%;">
                                    <?= Html::encode($sName) ?>:
                                </td>
                                <td class="adm-detail-content-cell-r" style="width: 50%;">
                                    <?php if ($sType == 'boolean') { ?>
                                        <?= Html::hiddenInput($sKey, 0) ?>
                                        <?= Html::checkbox($sKey, $sValue, [
                                            'value' => 1
                                        ]) ?>
                                    <?php } else if ($sType == 'string' || $sType == 'integer' || $sType == 'float') { ?>
                                        <input type="text" name="<?= $sKey ?>" value="<?= $sValue ?>" />
                                    <?php } else if ($sType == 'list') { ?>
                                        <select name="<?= $sKey ?>">
                                            <?php foreach ($arValues as $sValueKey => $sValueName) { ?>
                                                <option value="<?= Html::encode($sValueKey) ?>"<?= $sValue == $sValueKey ? ' selected="selected"' : ''?>>
                                                    <?= Html::encode($sValueName) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
        <?php } ?>
        <?php $oTabControl->Buttons() ?>
            <input type="submit" class="adm-btn-save" name="Apply" value="<?= Loc::getMessage('intec.universe.options.buttons.apply') ?>" />
            <input type="submit" name="Reset" value="<?= Loc::getMessage('intec.universe.options.buttons.reset') ?>" />
        <?php $oTabControl->End() ?>
    </form>*/ ?>