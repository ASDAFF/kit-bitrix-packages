<?
use Sotbit\Seometa\AutogenerationTable;
use Bitrix\Main\Localization\Loc;

// ��������� ��� ����������� �����:
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); // ������ ����� ������

// ��������� �������� ����
Loc::loadMessages(__FILE__);

// ������� ����� ������� �������� ������������ �� ������
$POST_RIGHT = $APPLICATION->GetGroupRight("sotbit.seometa");
// ���� ��� ���� - �������� � ����� ����������� ��������� �� ������
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

//$CCSeoMeta = new CCSeoMeta();
//if(!$CCSeoMeta->getDemo())
//    return false;

$sTableID = "b_sotbit_seometa_autogeneration"; // ID �������
$oSort = new CAdminSorting($sTableID, "ID", "desc"); // ������ ����������
$lAdmin = new CAdminList($sTableID, $oSort); // �������� ������ ������



// ******************************************************************** //
//                           ������                                     //
// ******************************************************************** //

// *********************** CheckFilter ******************************** //
// �������� �������� ������� ��� �������� ������� � ��������� �������
function CheckFilter()
{
    global $FilterArr, $lAdmin;
    foreach ($FilterArr as $f) global $$f;

    // � ������ ������ ��������� ������.
    // � ����� ������ ����� ��������� �������� ���������� $find_���
    // � � ������ ������������� ������ ���������� �� �����������
    // ����������� $lAdmin->AddFilterError('�����_������').

    return count($lAdmin->arFilterErrors) == 0; // ���� ������ ����, ������ false;
}
// *********************** /CheckFilter ******************************* //

// ������ �������� �������
$FilterArr = Array(
    "find",
    "find_type",
    "find_id",
    "find_name",
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);

// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
    // �������� ������ ���������� ��� ������� AutogenerationTable::getList() �� ������ �������� �������
    $arFilter = Array(
        "ID" => ($find != "" && $find_type == "id" ? $find : $find_id),
        "NAME" => $find_name,
    );

    foreach($arFilter as $key => $value)
    {
        if(empty($value))
            unset($arFilter[$key]);
    }
}



// ******************************************************************** //
//                ��������� �������� ��� ���������� ������              //
// ******************************************************************** //

// ���������� ����������������� ���������
if($lAdmin->EditAction() && $POST_RIGHT == "W")
{
    // ������� �� ������ ���������� ���������
    foreach($FIELDS as $ID => $arFields)
    {
        if(!$lAdmin->IsUpdated($ID))
            continue;
        
        // �������� ��������� ������� ��������
        $ID = IntVal($ID);
        if($ID > 0)
        {
            foreach($arFields as $key => $value)
                $arData[$key] = $value;
            
            $arData['DATE_CHANGE'] = new \Bitrix\Main\Type\DateTime();
            $result = AutogenerationTable::update($ID, $arData);
            if(!$result->isSuccess())
            {
                $lAdmin->AddGroupError(Loc::getMessage("SEOMETA_AUTOGENERATION_SAVE_ERROR"), $ID);
            }
        }
        else
        {
            $lAdmin->AddGroupError(Loc::getMessage("SEOMETA_AUTOGENERATION_SAVE_ERROR"), $ID);
        }
    }
}

// ��������� ��������� � ��������� ��������
if(($arID = $lAdmin->GroupAction()) && $POST_RIGHT == "W")
{
    // ���� ������� "��� ���� ���������"
    if($_REQUEST['action_target'] == 'selected')
    {
        $rsData = AutogenerationTable::getList(array(
            'select' => array('ID', 'NAME', 'DATE_CHANGE'),
            'filter' => $arFilter,
            'order' => array($by => $order),
        ));
        while($arRes = $rsData->Fetch())
            $arID[] = $arRes['ID'];
    }

    // ������� �� ������ ���������
    foreach($arID as $ID)
    {
        if(strlen($ID) <= 0)
            continue;
        $ID = IntVal($ID);
        
        // ��� ������� �������� �������� ��������� ��������
        switch($_REQUEST['action'])
        {
            // ��������
            case "delete":
                $result = AutogenerationTable::delete($ID);
                if(!$result->isSuccess())
                {
                    $lAdmin->AddGroupError(Loc::getMessage("SEOMETA_AUTOGENERATION_DELETE_ERROR"), $ID);
                }
                break;
        }
    }
}



// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //

// ������� ������
$rsData = AutogenerationTable::getList(array(
    'select' => array('ID', 'NAME', 'DATE_CHANGE'),
    'filter' => $arFilter,
    'order' => array($by => $order),
));

// ����������� ������ � ��������� ������ CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

// ���������� CDBResult �������������� ������������ ���������
$rsData->NavStart();

// �������� ����� ������������� ������� � �������� ������ $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(Loc::getMessage("rub_nav")));



// ******************************************************************** //
//                ���������� ������ � ������                            //
// ******************************************************************** //

$lAdmin->AddHeaders(array(
    array(
        "id" => "ID",
        "content" => "ID",
        "sort" => "ID",
        "align" => "right",
        "default" => true,
    ),
    array(
        "id" => "NAME",
        "content" => Loc::getMessage("SEOMETA_AUTOGENERATION_TABLE_NAME"),
        "sort" => "NAME",
        "default" => true,
    ),
    array(
        "id" => "DATE_CHANGE",
        "content" => Loc::getMessage("SEOMETA_AUTOGENERATION_TABLE_DATE_CHANGE"),
        "sort" => "DATE_CHANGE",
        "default" => true,
    ),
));

while($arRes = $rsData->NavNext(true, "f_"))
{
    // ������� ������. ��������� - ��������� ������ CAdminListRow
    $row =& $lAdmin->AddRow($f_ID, $arRes);

    // ����� �������� ����������� �������� ��� ��������� � �������������� ������

    // �������� NAME ����� ��������������� ��� �����, � ������������ �������
    $row->AddInputField("NAME", array("size" => 40));
    $row->AddViewField("NAME", '<a href="sotbit.seometa_autogeneration_edit.php?ID='.$f_ID.'&lang='.LANG.'">'.$f_NAME.'</a>');

    // ���������� ����������� ����
    $arActions = Array();

    // �������������� ��������
    $arActions[] = array(
        "ICON" => "edit",
        "DEFAULT" => true,
        "TEXT" => Loc::getMessage("SEOMETA_AUTOGENERATION_EDIT"),
        "ACTION" => $lAdmin->ActionRedirect("sotbit.seometa_autogeneration_edit.php?ID=".$f_ID)
    );

    // �������� ��������
    if ($POST_RIGHT >= "W")
    {
        $arActions[] = array(
            "ICON" => "delete",
            "TEXT" => Loc::getMessage("SEOMETA_AUTOGENERATION_DELETE"),
            "ACTION" => "if(confirm('".Loc::getMessage("SEOMETA_AUTOGENERATION_DELETE_CONFIRM")."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
        );
    }

    // ������� �����������
    $arActions[] = array("SEPARATOR" => true);
    // ���� ��������� ������� - �����������, �������� �����.
    if(is_set($arActions[count($arActions)-1], "SEPARATOR"))
        unset($arActions[count($arActions)-1]);

    // �������� ����������� ���� � ������
    $row->AddActions($arActions);
}

// ������ �������
$lAdmin->AddFooter(
    array(
        array("title" => Loc::getMessage("SEOMETA_AUTOGENERATION_LIST_SELECTED"), "value" => $rsData->SelectedRowsCount()), // ���-�� ���������
        array("counter" => true, "title" => Loc::getMessage("SEOMETA_AUTOGENERATION_LIST_CHECKED"), "value" => "0"), // ������� ��������� ���������
    )
);

// ��������� ��������
$lAdmin->AddGroupActionTable(Array(
    "delete" => "", // ������� ��������� ��������
    //"activate" => Loc::getMessage("SEOMETA_AUTOGENERATION_LIST_ACTIVATE"), // ������������ ��������� ��������
    //"deactivate" => Loc::getMessage("SEOMETA_AUTOGENERATION_LIST_DEACTIVATE"), // �������������� ��������� ��������
));



// ******************************************************************** //
//                ���������������� ����                                 //
// ******************************************************************** //

// ���������� ���� �� ������ ������ - ���������� ��������������
$aContext = array(
    array(
        "TEXT" => Loc::getMessage("SEOMETA_AUTOGENERATION_ADD_TEXT"),
        "LINK" => "sotbit.seometa_autogeneration_edit.php?lang=".LANG,
        "TITLE" => Loc::getMessage("SEOMETA_AUTOGENERATION_ADD_TITLE"),
        "ICON" => "btn_new",
    ),
);

// � ��������� ��� � ������
$lAdmin->AddAdminContextMenu($aContext);



// ******************************************************************** //
//                �����                                                 //
// ******************************************************************** //

// �������������� �����
$lAdmin->CheckListMode();

// ��������� ��������� ��������
$APPLICATION->SetTitle(Loc::getMessage("SEOMETA_AUTOGENERATION_TITLE"));

// �� ������� ��������� ���������� ������ � �����
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");



// ******************************************************************** //
//                ����� �������                                         //
// ******************************************************************** //

// �������� ������ �������
$oFilter = new CAdminFilter(
    $sTableID."_filter_autogeneration",
    array(
        "ID",
        Loc::getMessage("SEOMETA_AUTOGENERATION_FILTER_FIND_NAME"),
    )
);
?>

<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
    <?$oFilter->Begin();?>
    <tr>
        <td><b><?=Loc::getMessage("SEOMETA_AUTOGENERATION_FILTER_FIND")?></b></td>
        <td>
            <input type="text" size="25" name="find" value="<?echo htmlspecialchars($find)?>" title="<?=Loc::getMessage("SEOMETA_AUTOGENERATION_FILTER_FIND")?>">
            <?
            $arr = array(
                "reference" => array(
                    "ID",
                ),
                "reference_id" => array(
                    "id",
                )
            );
            echo SelectBoxFromArray("find_type", $arr, $find_type, "", "");
            ?>
        </td>
    </tr>
    <tr>
        <td><?="ID:"?></td>
        <td>
            <input type="text" name="find_id" size="47" value="<?echo htmlspecialchars($find_id)?>">
        </td>
    </tr>
    <tr>
        <td><?=Loc::getMessage("SEOMETA_AUTOGENERATION_FILTER_FIND_NAME").":"?></td>
        <td>
            <input type="text" name="find_name" size="47" value="<?echo htmlspecialchars($find_name)?>">
        </td>
    </tr>
    <?
    $oFilter->Buttons(array("table_id" => $sTableID, "url" => $APPLICATION->GetCurPage(), "form" => "find_form"));
    $oFilter->End();
    ?>
</form>
<?
if( CCSeoMeta::ReturnDemo() == 2){
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage("SEO_META_DEMO")?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
}
if( CCSeoMeta::ReturnDemo() == 3 || CCSeoMeta::ReturnDemo() == 0)
{
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage("SEO_META_DEMO_END")?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
    return '';
}
//<?
//if($CCSeoMeta->ReturnDemo() == 2) CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("SEO_META_DEMO"), 'HTML' => true));
//if($CCSeoMeta->ReturnDemo() == 3) CAdminMessage::ShowMessage(array("MESSAGE" => Loc::getMessage("SEO_META_DEMO_END"), 'HTML' => true));

// ������� ������� ������ ���������
$lAdmin->DisplayList();

// ���������� ��������
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>