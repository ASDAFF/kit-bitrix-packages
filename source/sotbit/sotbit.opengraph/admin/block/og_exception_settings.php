<?
$tabControl->AddSection("OG_EXCEPTIONS_URL", GetMessage($langPrefix."EXCEPTIONS_URL"));
$tabControl->addEditField("OG_EXCEPTION_URL[0]", "", false);
$tabControl->BeginCustomField("OG_EXCEPTION_BUTTON", GetMessage($langPrefix."EXCEPTION_BUTTON"), false);
?>
    <tr class="adm-detail-file-row">
        <td><? echo $tabControl->GetCustomLabelHTML() ?></td>
        <td>
            <input type="button" class="add_exception" value="<?=GetMessage($langPrefix."ADD_BUTTON_NAME");?>">
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_EXCEPTION_BUTTON");
?>