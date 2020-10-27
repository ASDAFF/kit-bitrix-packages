<?php
$tabControl->AddSection("OG_DEFAULT_VALUES", GetMessage($langPrefix."DEFAULT_VALUES"));
$tabControl->BeginCustomField("OG_TYPE_DEFAULT", GetMessage($langPrefix."OG_TYPE"), false);

$TYPES = array(
        'REFERENCE_ID' => array('product', 'article'),
        'REFERENCE' => array('product', 'article')
);

?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."OG_TYPE_NAME")?>
            </div>
            <div class="inline value">
<!--                <input name="OG_TYPE" value="--><?//=$arFields['OG_TYPE'];?><!--">-->
                <?=SelectBoxFromArray( 'OG_TYPE', $TYPES, $arFields['OG_TYPE'], '', 'style="width:147px;"')?>
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="OG_PROPS_ACTIVE[OG_TYPE]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_TYPE_DEFAULT");
$tabControl->BeginCustomField("OG_TITLE_DEFAULT", GetMessage($langPrefix."OG_OGP_TITLE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."OG_TITLE_NAME")?>
            </div>
            <div class="inline value">
                <input name="OG_TITLE" value="<?=$arFields['OG_TITLE'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="OG_PROPS_ACTIVE[OG_TITLE]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_TITLE_DEFAULT");
$tabControl->BeginCustomField("OG_DESCRIPTION_DEFAULT", GetMessage($langPrefix."OG_DESCRIPTION"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."OG_DESCRIPTION_NAME")?>
            </div>
            <div class="inline value">
                <input name="OG_DESCRIPTION" value="<?=$arFields['OG_DESCRIPTION'];?>">
            </div>
            <div class="inline checkbox">
                <input type="checkbox" name="OG_PROPS_ACTIVE[OG_DESCRIPTION]"
                       value="1"<? if(isset($arFields['OG_PROPS_ACTIVE']['OG_DESCRIPTION'])
                    && $arFields['OG_PROPS_ACTIVE']['OG_DESCRIPTION']): ?> checked="checked"<? endif; ?>>
                <?=GetMessage("KIT_OPENGRAPH_AСTIVE_PROP")?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_DESCRIPTION_DEFAULT");
$tabControl->BeginCustomField("OG_IMAGE_SECURE_URL_DEFAULT", GetMessage($langPrefix."OG_IMAGE_SECURE_URL"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."OG_IMAGE_SECURE_URL_NAME")?>
            </div>
            <div class="inline value">
                <input name="OG_IMAGE_SECURE_URL"
                       value="<?=$arFields['OG_IMAGE_SECURE_URL'];?>">
            </div>
            <div class="inline checkbox">
                <input type="checkbox" name="OG_PROPS_ACTIVE[OG_IMAGE_SECURE_URL]" value="1"<?
                if(isset($arFields['OG_PROPS_ACTIVE']['OG_IMAGE_SECURE_URL']) && $arFields['OG_PROPS_ACTIVE']['OG_IMAGE_SECURE_URL']): ?> checked="checked"<? endif; ?>>
                <?=GetMessage("KIT_OPENGRAPH_AСTIVE_PROP")?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_IMAGE_SECURE_URL_DEFAULT");
$tabControl->BeginCustomField("OG_DEFAULT_IMAGE", GetMessage($langPrefix."IMAGE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center;">
            <span style="vertical-align: middle;">
            <? echo $tabControl->GetCustomLabelHTML() ?>
            <input type="hidden" name="OG_PROPS_ACTIVE[OG_IMAGE]" value="1">
                </span>
            <div style="display: inline-block; vertical-align: middle;">
            <? echo \Bitrix\Main\UI\FileInput::createInstance(array(
                "name" => "OG_IMAGE",
                "description" => true,
                "upload" => true,
                "allowUpload" => "I",
                "medialib" => true,
                "fileDialog" => true,
                "cloud" => true,
                "delete" => true,
                "maxCount" => 1
            ))->show(isset($arFields['OG_IMAGE']) ? $arFields['OG_IMAGE'] : false, $bVarsFromForm); ?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_DEFAULT_IMAGE");