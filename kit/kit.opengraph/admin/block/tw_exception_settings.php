<?
$tabControl->AddSection("TW_EXCEPTIONS_URL", GetMessage($langPrefix."EXCEPTIONS_URL"));
$tabControl->addEditField("TW_EXCEPTION_URL[0]", "", false);
$tabControl->BeginCustomField("TW_EXCEPTION_BUTTON", GetMessage($langPrefix."EXCEPTION_BUTTON"), false);
?>
<tr class="adm-detail-file-row">
    <td><? echo $tabControl->GetCustomLabelHTML() ?></td>
    <td>
        <input type="button" class="add_exception" value="<?=GetMessage($langPrefix."ADD_BUTTON_NAME");?>">
    </td>
</tr>
<?
$tabControl->EndCustomField("TW_EXCEPTION_BUTTON");
?>