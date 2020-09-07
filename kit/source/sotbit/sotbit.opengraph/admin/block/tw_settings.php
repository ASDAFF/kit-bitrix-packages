<?php
$tabControl->AddSection("TW_DEFAULT_VALUES", GetMessage($langPrefix."DEFAULT_VALUES"));
$tabControl->BeginCustomField("TW_CARD_DEFAULT", GetMessage($langPrefix."TW_CARD"), false);

$TYPES = array(
    'REFERENCE_ID' => array('summary', 'summary_large_image'),
    'REFERENCE' => array('summary', 'summary_large_image')
);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_CARD_NAME")?>
            </div>
            <div class="inline value">
                <?=SelectBoxFromArray( 'TW_CARD', $TYPES, $arFields['TW_CARD'], '', 'style="width:147px;"')?>
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_CARD]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_CARD_DEFAULT");
$tabControl->BeginCustomField("TW_TITLE_DEFAULT", GetMessage($langPrefix."TW_TITLE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_TITLE_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_TITLE" value="<?=$arFields['TW_TITLE'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_TITLE]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_TITLE_DEFAULT");
$tabControl->BeginCustomField("TW_SITE_DEFAULT", GetMessage($langPrefix."TW_SITE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_SITE_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_SITE" value="<?=$arFields['TW_SITE'];?>">
            </div>
            <div class="inline checkbox">
                <input type="checkbox" name="TW_PROPS_ACTIVE[TW_SITE]"
                       value="1"<? if(isset($arFields['TW_PROPS_ACTIVE']['TW_SITE']) && $arFields['TW_PROPS_ACTIVE']['TW_SITE']): ?> checked="checked"<? endif; ?>>
                <?=GetMessage("SOTBIT_OPENGRAPH_AСTIVE_PROP")?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_SITE_DEFAULT");
$tabControl->BeginCustomField("TW_CREATOR_DEFAULT", GetMessage($langPrefix."TW_CREATOR"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_CREATOR_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_CREATOR" value="<?=$arFields['TW_CREATOR'];?>">
            </div>
            <div class="inline checkbox">
                <input type="checkbox" name="TW_PROPS_ACTIVE[TW_CREATOR]"
                       value="1"<? if(isset($arFields['TW_PROPS_ACTIVE']['TW_CREATOR']) && $arFields['TW_PROPS_ACTIVE']['TW_CREATOR']): ?> checked="checked"<? endif; ?>>
                <?=GetMessage("SOTBIT_OPENGRAPH_AСTIVE_PROP")?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_CREATOR_DEFAULT");
$tabControl->BeginCustomField("TW_DESCRIPTION_DEFAULT", GetMessage($langPrefix."TW_DESCRIPTION"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_DESCRIPTION_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_DESCRIPTION" value="<?=$arFields['TW_DESCRIPTION']?>">
            </div>
            <div class="inline checkbox">
                <input type="checkbox" name="TW_PROPS_ACTIVE[TW_DESCRIPTION]"
                       value="1"<? if(isset($arFields['TW_PROPS_ACTIVE']['TW_DESCRIPTION']) && $arFields['TW_PROPS_ACTIVE']['TW_DESCRIPTION']): ?> checked="checked"<? endif; ?>>
                <?=GetMessage("SOTBIT_OPENGRAPH_AСTIVE_PROP")?>
            </div>
            
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_DESCRIPTION_DEFAULT");
$tabControl->BeginCustomField("TW_IMAGE_ALT_DEFAULT", GetMessage($langPrefix."TW_IMAGE_ALT"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_IMAGE_ALT_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_IMAGE_ALT" value="<?=$arFields['TW_IMAGE_ALT'];?>">
            </div>
            <div class="inline checkbox">
                <input type="checkbox" name="TW_PROPS_ACTIVE[TW_IMAGE_ALT]"
                       value="1"<? if(isset($arFields['TW_PROPS_ACTIVE']['TW_IMAGE_ALT']) && $arFields['TW_PROPS_ACTIVE']['TW_IMAGE_ALT']): ?> checked="checked"<? endif; ?>>
                <?=GetMessage("SOTBIT_OPENGRAPH_AСTIVE_PROP")?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_IMAGE_ALT_DEFAULT");
$tabControl->BeginCustomField("TW_DEFAULT_IMAGE", GetMessage($langPrefix."IMAGE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center;">
            <span style="vertical-align: middle;">
            <? echo $tabControl->GetCustomLabelHTML() ?>
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_IMAGE]" value="1">
                </span>
            <div style="display: inline-block; vertical-align: middle;">
                <? echo \Bitrix\Main\UI\FileInput::createInstance(array(
                    "name" => "TW_IMAGE",
                    "description" => true,
                    "upload" => true,
                    "allowUpload" => "I",
                    "medialib" => true,
                    "fileDialog" => true,
                    "cloud" => true,
                    "delete" => true,
                    "maxCount" => 1
                ))->show(isset($arFields['TW_IMAGE']) ? $arFields['TW_IMAGE'] : false, $bVarsFromForm); ?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_DEFAULT_IMAGE");