<?
use Sotbit\Crosssell\Orm\CrosssellTable;
use \Sotbit\Crosssell\Orm\CrosssellCategoryTable;
use Sotbit\Crosssell\Orm\CrosssellProductsTable;
use Sotbit\Crosssell\Helper;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\PropertyTable;

// on page load set up //-----------------------------------------------------------------------------------------------------------------------------------------------
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
if(!Loader::includeModule('sotbit.crosssell')) {
    die();
}
Loader::includeModule('iblock');

$event = new Bitrix\Main\Event("sotbit.crosssell", "OnCrosssellCategorySettingsStart");
$event->send();

Loc::loadMessages(__FILE__);
$APPLICATION->SetTitle(Loc::GetMessage("CROSSELL_PAGE_TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

CJSCore::Init(array("jquery"));
$sites = \SotbitCrosssell::getSites();
?>
    <link rel="stylesheet" href="/bitrix/css/sotbit.crosssell/style.css">
<?
if (isset($_GET['site']) && (strlen($_GET['site']) > 0)){
    $siteId = $_GET['site'];
} else {
    $siteId = 's1';
}

if (isset($_GET['lang']) && (strlen($_GET['lang']) > 0)){
    $lang = $_GET['lang'];
} else {
    $lang = 'ru';
}

// Setting edit request from list ID //-----------------------------------------------------------------------------------------------------------------------------------------------
$condition = array();
$arResult = array();
$is_sort_by_other = false;
$custum_other_sort_by = '';
$isEditing = false;
if(isset($_REQUEST['ID'])) {
    $isEditing = true;
    $id = $_REQUEST['ID'];
    $rs = CrosssellTable::getById($id);
    $arResult = $rs->fetch();
    $obCond4 = new \SotbitCrosssellCatalogCondTree();
    $boolCond = $obCond4->Init( BT_COND_MODE_PARSE, BT_COND_BUILD_CATALOG, array () );
    $condParsed = $obCond4->Parse( unserialize($arResult['RULE2']) );
    $condition = array(
        'NAME' => $arResult['NAME'],
        'RULE1' => $arResult['RULE1'],
        'RULE2' => $condParsed,
        'RULE3' => $arResult['RULE3'],
    );

    $sort_by_options = ['name', 'index', 'date', 'id', 'rand'];
    if (!in_array($arResult['SORT_BY'], $sort_by_options)) {
        $is_sort_by_other = true;
        $custum_other_sort_by = $arResult['SORT_BY'];
        $arResult['SORT_BY'] = 'other';
    }
}

// Setting type //-----------------------------------------------------------------------------------------------------------------------------------------------
$TYPE = $_REQUEST['TYPE'];

// Setting category ID //-----------------------------------------------------------------------------------------------------------------------------------------------
$CATEGORY_ID = 0;
if ($_POST['tab_1_category']) {
    $CATEGORY_ID = $_POST['tab_1_category'];
}

$bVarsFromForm = false;
$condiFromForm = false;

// save & apply handler //-----------------------------------------------------------------------------------------------------------------------------------------------
if($_POST['save'] != "" || $_POST['apply'] != "") {
    $obCond3 = new \SotbitCrosssellCatalogCondTree();
    $boolCond = $obCond3->Init( BT_COND_MODE_PARSE, BT_COND_BUILD_CATALOG, array () );
    $cond1 = $obCond3->Parse( $_POST['rule1'] );
    $cond2 = $obCond3->Parse( $_POST['rule2'] );
    $TMP_SAVE_COND = [
        'rule1' => $cond1,
        'rule2' => $cond2
    ];
    $bVarsFromForm = true;
    $condiFromForm = true;
    if($_POST['crosssell_tab1_sort'] != null & $_POST['NAME'] != null & count($_POST['crosssell_tab1_site']) > 0 ) {
        $firstImg = '';
        $secondImg = '';
        $sortBy = $_POST['tab_3_sort_by'];
        $isSafe = true;

        if(is_array($_POST['crosssell_tab1_first_img']))
        {
            $arELEMENT_FILE = \CIBlock::makeFileArray($_POST['crosssell_tab1_first_img']);
            $firstImg = CFile::SaveFile($arELEMENT_FILE, "sotbot.crosssell");
        } elseif(is_numeric($_POST['crosssell_tab1_first_img'])) {
            if(isset($_POST['crosssell_tab1_first_img_del']) && $_POST["crosssell_tab1_first_img_del"] === "Y") {
                $firstImg = '';
            } else {
                $firstImg = $_POST['crosssell_tab1_first_img'];
            }
        }

        if(is_array($_POST['crosssell_tab1_detailed_img']))
        {
            $arELEMENT_FILE = \CIBlock::makeFileArray($_POST['crosssell_tab1_detailed_img']);
            $secondImg = CFile::SaveFile($arELEMENT_FILE, "sotbit.crosssell");
        } elseif(is_numeric($_POST['crosssell_tab1_detailed_img'])) {
            if(isset($_POST['crosssell_tab1_detailed_img_del']) && $_POST["crosssell_tab1_detailed_img_del"] === "Y") {
                $secondImg = '';
            } else {
                $secondImg = $_POST['crosssell_tab1_detailed_img'];
            }
        }

        if($_POST['tab_3_sort_by'] == 'other') {
            $sortBy = $_POST['tab_3_sort_by_other'];
        }
        if ($_POST['tab_3_sort_by'] == ''){
            $sortBy = 'rand';
        }

        // Reset rule empty flag
        \SotbitCrosssellCatalogCondTree::setEmptyFlag();
        // Checking if condition is empty // ----------------------------------------------------------------------------------------------------------------------
        if($TYPE == 'COLLECTION') {
            if(\SotbitCrosssellCatalogCondTree::checkingForEmptiness($cond1['CHILDREN']) == false) {
                echo Helper\CrosssellNotice::getError(Loc::getMessage("CROSSELL_PAGE_ERROR_CONDITION_EMPTHY"));
                $isSafe = false;
            }
            // PRODUCT
            $numberOfProductsMatched = 0;
            $crossell = new \SotbitCrosssell();
            $arFilter = $crossell->getFilter($cond1);
            $queryGetElement = CIBlockElement::GetList(
                array(),
                $arFilter,
                false,
                false,
                array()
            );
            while ($product = $queryGetElement->fetch()) {
                $numberOfProductsMatched++;
            }
        }

        // Checking if condition has IBLOCK or emthy // ----------------------------------------------------------------------------------------------------------------------
        if($TYPE == 'CROSSSELL') {
            $crossell = new \SotbitCrosssell();
            if(\SotbitCrosssellCatalogCondTree::checkingForEmptiness($cond1['CHILDREN']) == false) {
                echo Helper\CrosssellNotice::getError(Loc::getMessage("CROSSELL_PAGE_ERROR_CONDITION_EMPTHY"));
                $isSafe = false;
            }
            // PRODUCT
            $numberOfProductsMatched = 0;
            $productsCrosssell = array();
            $arFilter1 = $crossell->getFilter($cond1);
//            echo "<pre>";
//            print_r($arFilter1);
//            print_r($cond2);
//            echo "</pre>";
//            die;
//            $arFilter2 = $crossell->getFilter($cond2);
            $queryGetElementCond1 = CIBlockElement::GetList(
                array(),
                $arFilter1,
                false,
                false,
                array()
            );
//            $queryGetElementCond2 = CIBlockElement::GetList(
//                array(),
//                $arFilter2,
//                false,
//                false,
//                array()
//            );
            while ($product = $queryGetElementCond1->fetch()) {
                array_push($productsCrosssell, array(
                    "ID" => $product['ID'],
                    "IBLOCK_ID" => $product['IBLOCK_ID']
                ));
                $numberOfProductsMatched++;
            }
//            while ($product = $queryGetElementCond2->fetch()) {
//                $numberOfProductsMatched++;
//            }
        }

        $extraSettings = array();
        if (is_string($_POST['ITEM_TEMPLATE']) && (strlen($_POST['ITEM_TEMPLATE']) > 0)){
            $extraSettings['ITEM_TEMPLATE'] = $_POST['ITEM_TEMPLATE'];
        }

        if (is_string($_POST['USE_SLIDER']) && (strlen($_POST['USE_SLIDER']) > 0)){
            $extraSettings['USE_SLIDER'] = $_POST['USE_SLIDER'];
        }

        if($isSafe) {
            if ($sortBy == 'rand'){
                $sortOrder = 'rand';
            } else {
                $sortOrder = $_POST['tab_3_sort_order'];
            }
            $arFields = Array (
                "Active" => $_POST['crosssell_tab1_active'],
                "SITES" => serialize($_POST['crosssell_tab1_site']),
                "SORT" => $_POST['crosssell_tab1_sort'],
                "NAME" => $_POST['NAME'],
                "SYMBOL_CODE" => $_POST['CODE'],
                "FOREIGN_CODE" => $_POST['crosssell_tab1_foreign_code'],
                "FIRST_IMG" => $firstImg,
                "SECOND_IMG" => $secondImg,
                //"EXTRA_SETTINGS" => serialize($extraSettings),
                "RULE1" => serialize($cond1),
                "RULE2" => ($TYPE == 'CROSSSELL') ? serialize($_POST['rule2']) : '',
                "RULE3" => ($TYPE == 'CROSSSELL') ? serialize($_POST['tab_3_property_link']) : '',
                "SORT_BY" => $sortBy,
                "SORT_ORDER" => $sortOrder,
                'NUMBER_PRODUCTS' => $_POST['tab_3_number_products'],
                'CATEGORY_ID' => $CATEGORY_ID,
                "FIRST_IMG_DESC" => $_POST['crosssell_tab1_first_img_desc'],
                "SECOND_IMG_DESC" => $_POST['crosssell_tab1_detailed_img_desc'],
                "TYPE_BLOCK" => ($isEditing) ? $arResult['TYPE_BLOCK'] : $_POST['tab_1_type'],
                "PRODUCTS" => ($TYPE == "CROSSSELL") ? serialize($productsCrosssell) : '',
                "NUMBER_OF_MATCHED_PRODUCTS" => $numberOfProductsMatched
            );

            if(isset($_REQUEST['ID'])) {
                $result = CrosssellTable::update($_REQUEST['ID'], $arFields);
                if($result) {
                    if($_REQUEST['apply']) {
                        LocalRedirect("/bitrix/admin/sotbit_crosssell.php?lang=" . $lang . "&site=" . $siteId ."&ID=" . $_REQUEST['ID'] . '&TYPE=' . $TYPE . '&SUCCESS=Y&CATEGORY_ID='.$CATEGORY_ID);
                    }
                    if($_REQUEST['save']) {
                        LocalRedirect("/bitrix/admin/sotbit_crosssell_list.php?lang=" . $lang . "&site=" . $siteId .'&CATEGORY_ID='. $CATEGORY_ID);
                    }
                } else {
                    echo Helper\CrosssellNotice::getError(Loc::getMessage("CROSSELL_PAGE_ERROR_SAVE_TO_DB"));
                }
            } else {
                $result = CrosssellTable::add($arFields);
                $ID = $result->getId();
                if($result->isSuccess()) {
                    if($_REQUEST['apply']) {
                        LocalRedirect("/bitrix/admin/sotbit_crosssell.php?lang=" . $lang . "&site=" . $siteId ."&ID=" . $ID . '&TYPE=' . $TYPE . (isset($_REQUEST["CATEGORY_ID"]) ? '&CATEGORY_ID='.$_REQUEST["CATEGORY_ID"] : ''));
                        echo Helper\CrosssellNotice::getSuccess(Loc::getMessage("CROSSELL_PAGE_SUCCESS_SAVED"));
                    }
                    if($_REQUEST['save']) {
                        LocalRedirect("/bitrix/admin/sotbit_crosssell_list.php?lang=" . $lang . "&site=" . $siteId . (isset($_REQUEST["CATEGORY_ID"]) ? '&CATEGORY_ID='.$_REQUEST["CATEGORY_ID"] : ''));
                    }
                } else {
                    echo Helper\CrosssellNotice::getError(Loc::getMessage("CROSSELL_PAGE_ERROR_SAVE_TO_DB"));
                }
            }
        }

    } else {
        if($_POST['crosssell_tab1_sort'] == null) {
            ?>
                <?=Helper\CrosssellNotice::getError(Loc::getMessage("CROSSELL_PAGE_ERROR_REQUIRED_FIELD") . ': ' . Loc::getMessage("CROSSELL_PAGE_TAB_1_SORT"));?>
            <?
        }
        if($_POST['NAME'] == null) {
            ?>
                <?=Helper\CrosssellNotice::getError(Loc::getMessage("CROSSELL_PAGE_ERROR_REQUIRED_FIELD") . ': ' . Loc::getMessage("CROSSELL_PAGE_TAB_1_NAME"));?>
            <?
        }
        if($_POST['crosssell_tab1_site'] == null) {
            ?>
                <?=Helper\CrosssellNotice::getError(Loc::getMessage("CROSSELL_PAGE_ERROR_REQUIRED_FIELD") . ': ' . Loc::getMessage("CROSSELL_PAGE_TAB_1_SITE"));?>
            <?
        }

    }
}

if(isset($_REQUEST['SUCCESS']) && $isSafe !== false) {
    CAdminMessage::ShowMessage(array(
        "MESSAGE" => Loc::getMessage("CROSSELL_PAGE_SUCCESS_SAVED"),
        "TYPE" => "OK"
    ));
}

// AutoComplete //-----------------------------------------------------------------------------------------------------------------------------------------------
$bFileman = Loader::includeModule("fileman");
$arTranslit = Array(
    'UNIQUE' => 'N',
    'TRANSLITERATION' => 'Y',
    'TRANS_LEN' => '100',
    'TRANS_CASE' => 'L',
    'TRANS_SPACE' => '_',
    'TRANS_OTHER' => '_',
    'TRANS_EAT' => 'Y',
    'USE_GOOGLE' => 'N'
);
$bLinked = (!strlen($str_TIMESTAMP_X) || $bCopy) && $_POST["linked_state"]!=='N';

CJSCore::Init(array('translit'));
?>
<script type="text/javascript">
    var linked=<?if($bLinked) echo 'true'; else echo 'false';?>;
    function set_linked()
    {
        linked=!linked;

        var name_link = document.getElementById('name_link');
        if(name_link)
        {
            if(linked)
                name_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
            else
                name_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
        }
        var code_link = document.getElementById('code_link');
        if(code_link)
        {
            if(linked)
                code_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
            else
                code_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
        }
        var linked_state = document.getElementById('linked_state');
        if(linked_state)
        {
            if(linked)
                linked_state.value='Y';
            else
                linked_state.value='N';
        }
    }
    var oldValue = '';
    function transliterate()
    {
        if(linked)
        {
            var from = document.getElementById('NAME');
            var to = document.getElementById('CODE');
            if(from && to && oldValue != from.value)
            {
                BX.translit(from.value, {
                    'max_len' : <?echo intval($arTranslit['TRANS_LEN'])?>,
                    'change_case' : '<?echo $arTranslit['TRANS_CASE']?>',
                    'replace_space' : '<?echo $arTranslit['TRANS_SPACE']?>',
                    'replace_other' : '<?echo $arTranslit['TRANS_OTHER']?>',
                    'delete_repeat_replace' : <?echo $arTranslit['TRANS_EAT'] == 'Y'? 'true': 'false'?>,
                    'use_google' : <?echo $arTranslit['USE_GOOGLE'] == 'Y'? 'true': 'false'?>,
                    'callback' : function(result){to.value = result; setTimeout('transliterate()', 250); }
                });
                oldValue = from.value;
            }
            else
            {
                setTimeout('transliterate()', 250);
            }
        }
        else
        {
            setTimeout('transliterate()', 250);
        }
    }
    transliterate();
</script>
<?

// select-tab3 //-----------------------------------------------------------------------------------------------------------------------------------------------
$sortOrderOptions = [
    'REFERENCE' => [
        'asc' => Loc::getMessage("CROSSELL_PAGE_TAB_3_SORT_ORDER_ASC"),
        'desc' => Loc::getMessage("CROSSELL_PAGE_TAB_3_SORT_ORDER_DESC"),
    ],
    'REFERENCE_ID' => [
        'asc' => 'asc',
        'desc' => 'desc',
    ]
];

$typeOptions = [
    'REFERENCE' => [
        'crosssell' => Loc::getMessage("CROSSELL_PAGE_TAB_1_TYPE_CROSSSELL_SELL"),
        'collections' => Loc::getMessage("CROSSELL_PAGE_TAB_1_TYPE_COLLECTION"),
    ],
    'REFERENCE_ID' => [
        'crosssell' => 'CROSSSELL',
        'collections' => 'COLLECTION'
    ]
];

$sortByOptions = [
    'REFERENCE' => [
        'name' => Loc::getMessage("CROSSELL_PAGE_TAB_3_SORT_BY_NAME"),
        'index' => Loc::getMessage("CROSSELL_PAGE_TAB_3_SORT_BY_INDEX"),
        'date' => Loc::getMessage("CROSSELL_PAGE_TAB_3_SORT_BY_DATE"),
        'id' => Loc::getMessage("CROSSELL_PAGE_TAB_3_SORT_BY_ID"),
        'other' =>  Loc::getMessage("CROSSELL_PAGE_TAB_3_SORT_BY_OTHER"),
        'rand' => Loc::getMessage("CROSSELL_PAGE_TAB_3_SORT_RAND"),
    ],
    'REFERENCE_ID' => [
        'name' => 'name',
        'index' => 'index',
        'date' => 'date',
        'id' => 'id',
        'other' => 'other',
        'rand' => 'rand'
    ]
];

//Получаем список инфоблоков
$infoBlockList = CIBlock::GetList(
    Array(),
    Array(
        'SITE_ID'=>$siteId,
        'ACTIVE'=>'Y',
    ), true
);

$infoBlockArray = array();
while ($res = $infoBlockList->Fetch()){
    $infoBlockArray[$res['ID']] = $res['NAME'];
}

$propertyFields = [
    'REFERENCE' => array(),
    'REFERENCE_ID' => array()
];

$res = PropertyTable::getList(array(
        'filter' => array(
                "LOGIC" => "OR",
                array('PROPERTY_TYPE' => 'E')
            ),
        'order' => array(
                'NAME' => 'asc',
            )
    )
);

$properties = array();

$userTypes = array('EList', 'ElementXmlID', 'EAutocomplete', 'SKU', '', 'RegionsList');

while($res_arr = $res->Fetch()){
    if (in_array($res_arr['USER_TYPE'], $userTypes)){
        array_push($properties, $res_arr);
    }
}

$properties = array_msort($properties, array('IBLOCK_ID' => SORT_ASC));

function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

}

foreach ($properties as $property){
    $groupName = Loc::getMessage("CROSSELL_PAGE_TAB_3_GROUP_LABEL") . $infoBlockArray[$property['IBLOCK_ID']] . " [" . $property['IBLOCK_ID'] . "]";
    $propertyFields['REFERENCE'][$groupName][$property['ID']] = Loc::getMessage("CROSSELL_PAGE_TAB_3_PROPERTY_LABEL") . $property['NAME'] . " [" . $property['ID'] . "]";
    $propertyFields['REFERENCE_ID'][$groupName][$property['ID']] = $property['ID'] . ":" . $property['IBLOCK_ID'];
}

function CustomSelectBoxMFromArray($strBoxName, $a, $arr, $strDetText = "", $strDetText_selected = false, $size = "5", $field1="class='typeselect'")
{
    $strReturnBox = "<select ".$field1." multiple name=\"".$strBoxName."\" id=\"".$strBoxName."\" size=\"".$size."\">";

    if(array_key_exists("REFERENCE_ID", $a))
        $reference_id = $a["REFERENCE_ID"];
    elseif(array_key_exists("reference_id", $a))
        $reference_id = $a["reference_id"];
    else
        $reference_id = array();

    if(array_key_exists("REFERENCE", $a))
        $reference = $a["REFERENCE"];
    elseif(array_key_exists("reference", $a))
        $reference = $a["reference"];
    else
        $reference = array();

    if($strDetText <> '')
    {
        $strReturnBox .= "<option ";
        if($strDetText_selected)
            $strReturnBox .= " selected ";
        $strReturnBox .= " value='NOT_REF'>".$strDetText."</option>";
    }
    foreach($reference_id as $key => $value)
    {
        if(is_array($value)){
            $groupName = $key;
            $strReturnBox .= "<optgroup label='" . $groupName . "'>";
            foreach ($value as $k => $v) {
                $sel = (is_array($arr) && in_array($v, $arr)? "selected" : "");
                $strReturnBox .= "<option value=\"".htmlspecialcharsbx($v)."\" ".$sel.">". htmlspecialcharsbx($reference[$groupName][$k])."</option>";
            }
            $strReturnBox .= "</optgroup>";
        }
        else{
            $sel = (is_array($arr) && in_array($value, $arr)? "selected" : "");
            $strReturnBox .= "<option value=\"".htmlspecialcharsbx($value)."\" ".$sel.">". htmlspecialcharsbx($reference[$key])."</option>";
        }
    }
    $strReturnBox .= "</select>";
    return $strReturnBox;
}

// back menu //-----------------------------------------------------------------------------------------------------------------------------------------------

if($APPLICATION->GetGroupRight('sotbit.crosssell') == "W")
{
    if($TYPE == 'CROSSSELL') {
        $aMenu= array(
            array(
                "TEXT" => GetMessage( "SOTBIT_CROSSSELL_EDIT_LIST" ),
                "TITLE" => GetMessage( "SOTBIT_CROSSSELL_EDIT_LIST_TITLE" ),
                "LINK" => '/bitrix/admin/sotbit_crosssell_list.php?lang='.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : '') . (isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['CATEGORY_ID'].'&apply_filter=Y' : ''),
                "ICON" => "btn_list"
            ),

        );

        if($_REQUEST['ID'])
        {
            $aMenu[] = array(
                "TEXT" => GetMessage( "SOTBIT_CROSSSELL_GENERATE" ),
                "TITLE" => GetMessage( "SOTBIT_CROSSSELL_GENERATE" ),
                "ICON" => "btn_generate",
                "LINK" => 'javascript:void(0)'
            );
        }

    } else {
        $aMenu= array(
            array(
                "TEXT" => GetMessage( "SOTBIT_CROSSSELL_EDIT_LIST" ),
                "TITLE" => GetMessage( "SOTBIT_CROSSSELL_EDIT_LIST_TITLE" ),
                "LINK" => '/bitrix/admin/sotbit_crosssell_list.php?lang='.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : '') . (isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['CATEGORY_ID'].'&apply_filter=Y' : ''),
                "ICON" => "btn_list"
            )
        );


    }

    $aMenu[] = array(
        "TEXT" => GetMessage( "SOTBIT_CROSSSELL_NEW_BLOCK"),
        "LINK" => "/bitrix/admin/sotbit_crosssell.php?lang=".SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : '') . (isset($_REQUEST["TYPE"]) ? '&TYPE='.$_REQUEST["TYPE"] : '') . (isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['CATEGORY_ID'].'&apply_filter=Y' : ''),
        "ICON" => "btn_new",
    );


    $aMenu[] = array(
        "TEXT" => GetMessage( "SOTBIT_CROSSSELL_NEW_CATEGORY"),
        "LINK" => "/bitrix/admin/sotbit_crosssell_category.php?lang=".SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : ''),
        "ICON" => "btn_new",
    );

    if($_REQUEST['ID'])
    {
        $urlDelete = '/bitrix/admin/sotbit_crosssell_list.php?lang'.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : '').'&sessid='.bitrix_sessid().'&type=E&delete='.$_REQUEST['ID'];

        $aMenu[] = array(
            "TEXT" => GetMessage( "SOTBIT_CROSSSELL_DELETE_BLOCK"),
            "ONCLICK" => "javascript:if(confirm('".GetMessageJS("SOTBIT_CROSSSELL_NEW_CATEGORY_DELETE_BLOCK_TEXT")."'))top.window.location.href='".CUtil::JSEscape($urlDelete)."'",
            "ICON" => "btn_delete",
        );
    }

    $context = new CAdminContextMenu( $aMenu );
    $context->Show();
}



// TABS //-----------------------------------------------------------------------------------------------------------------------------------------------
if($TYPE == 'CROSSSELL') {
    $aTabs = array(
        array(
            "DIV" => "crosssell_tab",
            "TAB" => Loc::getMessage("CROSSELL_PAGE_TAB_1"),
            "ICON" => "",
            "TITLE" => Loc::getMessage("CROSSELL_PAGE_TAB_1")
        ),
        array(
            "DIV" => "gen_product_tab",
            "TAB" => Loc::getMessage("CROSSELL_PAGE_TAB_2"),
            "ICON" => "",
            "TITLE" => Loc::getMessage("CROSSELL_PAGE_TAB_2")
        ),
        array(
            "DIV" => "product_for_sell",
            "TAB" => Loc::getMessage("CROSSELL_PAGE_TAB_3"),
            "ICON" => "",
            "TITLE" => Loc::getMessage("CROSSELL_PAGE_TAB_3")
        ),
    );
} else {
    $aTabs = array(
        array(
            "DIV" => "crosssell_tab",
            "TAB" => Loc::getMessage("COLLECTION_PRODUCTS_COLLECTIONS"),
            "ICON" => "",
            "TITLE" => Loc::getMessage("COLLECTION_PRODUCTS_COLLECTIONS")
        ),
        array(
            "DIV" => "gen_product_tab",
            "TAB" => Loc::getMessage("COLLECTION_PRODUCTS"),
            "ICON" => "",
            "TITLE" => Loc::getMessage("COLLECTION_PRODUCTS")
        ),
    );
}

$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->Begin();


// crosssell tab1 //-----------------------------------------------------------------------------------------------------------------------------------------------
$tabControl->BeginNextFormTab();

if($isEditing) {
    $tabControl->BeginCustomField( "tab_1_type", Loc::getMessage('CROSSELL_PAGE_TAB_1_TYPE'), false );
    ?>
    <tr id="tab_1_type">
        <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="60%">
            <?echo SelectBoxFromArray('tab_1_type', $typeOptions, ($TYPE == 'CROSSSELL') ? 'CROSSSELL' : 'COLLECTION' ,'',"class='typeselect' disabled",'','style="min-width:350px"');?>
        </td>
    </tr>
    <?
    $tabControl->EndCustomField( "tab_1_type" );
} else {
    $tabControl->BeginCustomField( "tab_1_type", Loc::getMessage('CROSSELL_PAGE_TAB_1_TYPE'), false );
    ?>
    <tr id="tab_1_type">
        <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="60%">
            <?echo SelectBoxFromArray('tab_1_type', $typeOptions, ($TYPE == 'CROSSSELL') ? 'CROSSSELL' : 'COLLECTION' ,'', false,'','style="min-width:350px"');?>
        </td>
    </tr>
    <?
    $tabControl->EndCustomField( "tab_1_type" );
}

if(isset($arResult['Active'])) {
    $tabControl->AddCheckBoxField("crosssell_tab1_active", Loc::getMessage("CROSSELL_PAGE_TAB_1_ACTIVE"), false, ['Y', 'N'], ($arResult['Active'] == "Y") ? true : false);

} else {
    $tabControl->AddCheckBoxField("crosssell_tab1_active", Loc::getMessage("CROSSELL_PAGE_TAB_1_ACTIVE"), false, ['Y', 'N'], true);
}

$tabControl->BeginCustomField( "crosssell_tab1_site", Loc::getMessage("CROSSELL_PAGE_TAB_1_SITE"). ':', true );

?>
<tr id="crosssell_tab1_site">
    <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
    <td>
        <?=CLang::SelectBoxMulti("crosssell_tab1_site", (isset($arResult['SITES'])) ? unserialize($arResult['SITES']) : ((isset($_REQUEST['crosssell_tab1_site'])) ? $_REQUEST['crosssell_tab1_site'] : $sites ))?>
    </td>
</tr>
<?
$tabControl->EndCustomField( "crosssell_tab1_site" );

// name & symbol code //
if($arTranslit["TRANSLITERATION"] == "Y")
{
    $tabControl->BeginCustomField("NAME", GetMessage("IBLOCK_FIELD_NAME").":", true);
    ?>
    <tr id="tr_NAME">
        <td><?echo $tabControl->GetCustomLabelHTML()?></td>
        <td style="white-space: nowrap;">
            <input type="text" size="50" name="NAME" id="NAME" maxlength="255" value="<?echo (isset($arResult['NAME'])) ? $arResult['NAME']: ((isset($_REQUEST['NAME'])) ? $_REQUEST['NAME'] : $str_NAME) ?>"><img id="name_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
        </td>
    </tr>
    <?
    $tabControl->EndCustomField("NAME",
        '<input type="hidden" name="NAME" id="NAME" value="'. (isset($arResult['NAME'])) ? $arResult['NAME'] : ((isset($_REQUEST['NAME'])) ? $_REQUEST['NAME'] : $str_NAME).'">'
    );

    $tabControl->BeginCustomField("CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y");
    ?>
    <tr id="tr_CODE">
        <td><?echo $tabControl->GetCustomLabelHTML()?></td>
        <td style="white-space: nowrap;">
            <input type="text" size="50" name="CODE" id="CODE" maxlength="255" value="<?echo (isset($arResult['SYMBOL_CODE'])) ? $arResult['SYMBOL_CODE'] : $str_CODE?>"><img id="code_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
        </td>
    </tr>
    <?
    $tabControl->EndCustomField("CODE",
        '<input type="hidden" name="CODE" id="CODE" value="'. (isset($arResult['SYMBOL_CODE'])) ? $arResult['SYMBOL_CODE'] : $str_CODE . '">'
    );
}
else
{
    $tabControl->AddEditField("NAME", GetMessage("IBLOCK_FIELD_NAME").":", true, array("size" => 50, "maxlength" => 255), (isset($arResult['NAME'])) ? $arResult['NAME'] : $str_NAME);
    $tabControl->AddEditField("CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y", array("size" => 20, "maxlength" => 255), (isset($arResult['SYMBOL_CODE'])) ? $arResult['SYMBOL_CODE'] : $str_CODE);
}

$tabControl->AddEditField("crosssell_tab1_foreign_code", Loc::GetMessage( "CROSSELL_PAGE_TAB_1_FOREIGN_CODE" ), false, array(
    "size" => 50,
    "maxlength" => 255
), (isset($arResult['FOREIGN_CODE'])) ? $arResult['FOREIGN_CODE'] : ((isset($_REQUEST['crosssell_tab1_foreign_code'])) ? $_REQUEST['crosssell_tab1_foreign_code'] : false));
$tabControl->AddEditField("crosssell_tab1_sort", Loc::GetMessage( "CROSSELL_PAGE_TAB_1_SORT") . ':', true, array(
    "size" => 15,
    "maxlength" => 255
), (isset($arResult['SORT'])) ? $arResult['SORT'] : ((isset($_REQUEST['crosssell_tab1_sort'])) ? $_REQUEST['crosssell_tab1_sort'] : 100));


//Section List

$categoryList = array();
$categoryTable = CrosssellCategoryTable::getList();

$categoryList['REFERENCE'] = array();
$categoryList['REFERENCE_ID'] = array();

array_push($categoryList['REFERENCE'], Loc::getMessage('CROSSELL_PAGE_TAB_1_ROOT_CATEGORY'));
array_push($categoryList['REFERENCE_ID'], 0);


while ($category = $categoryTable->fetch()){
    array_push($categoryList['REFERENCE'], $category['NAME']);
    array_push($categoryList['REFERENCE_ID'], $category['ID']);
}

$tabControl->BeginCustomField( "tab_1_category", Loc::getMessage('CROSSELL_PAGE_TAB_1_CATEGORY'), false );

if(!isset($arResult['CATEGORY_ID'])) {
    $arResult['CATEGORY_ID'] = 0;
}

?>
<tr id="tab_1_category">
    <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
    <td width="60%">
        <?echo SelectBoxFromArray('tab_1_category', $categoryList, intval($arResult['CATEGORY_ID']));?>
    </td>
</tr>
<?
$tabControl->EndCustomField( "tab_1_category" );



$tabControl->BeginCustomField("crosssell_tab1_first_img", GetMessage('CROSSELL_PAGE_TAB_1_FIRST_IMG'),false);
    ?>
	<tr class="adm-detail-file-row">
		<td><?echo $tabControl->GetCustomLabelHTML()?></td>
		<td><?echo \Bitrix\Main\UI\FileInput::createInstance(array(
            "name" => "crosssell_tab1_first_img",
            "description" => true,
            "upload" => true,
            "allowUpload" => "I",
            "medialib" => true,
            "fileDialog" => true,
            "cloud" => true,
            "delete" => true,
            "maxCount" => 1
            ))->show(($bVarsFromForm ? ($_REQUEST["crosssell_tab1_first_img"]) : $arResult['FIRST_IMG']), $bVarsFromForm);?>
        </td>
    </tr>
    <?
$tabControl->EndCustomField("crosssell_tab1_first_img");
$tabControl->AddTextField("crosssell_tab1_first_img_desc", GetMessage('CROSSELL_PAGE_TAB_1_FIRST_DESC'), (isset($arResult['FIRST_IMG_DESC'])) ? $arResult['FIRST_IMG_DESC'] : ((isset($_REQUEST['crosssell_tab1_first_img_desc'])) ? $_REQUEST['crosssell_tab1_first_img_desc'] : ''), [
        'cols' => 50,
        'rows' => 4
]);
$tabControl->BeginCustomField("crosssell_tab1_detailed_img", GetMessage('CROSSELL_PAGE_TAB_1_DETAILED_IMG'),false);
    ?>
    <tr class="adm-detail-file-row">
        <td><?echo $tabControl->GetCustomLabelHTML()?></td>
        <td><?echo \Bitrix\Main\UI\FileInput::createInstance(array(
                "name" => "crosssell_tab1_detailed_img",
                "description" => true,
                "upload" => true,
                "allowUpload" => "I",
                "medialib" => true,
                "fileDialog" => true,
                "cloud" => true,
                "delete" => true,
                "maxCount" => 1
            ))->show(($bVarsFromForm ? $_REQUEST["crosssell_tab1_detailed_img"] : $arResult['SECOND_IMG']), $bVarsFromForm);?>
        </td>
    </tr>
    <?
$tabControl->EndCustomField("crosssell_tab1_detailed_img");
$tabControl->AddTextField("crosssell_tab1_detailed_img_desc", GetMessage('CROSSELL_PAGE_TAB_1_DETAILED_DESC'), (isset($arResult['SECOND_IMG_DESC'])) ? $arResult['SECOND_IMG_DESC'] : ((isset($_REQUEST['crosssell_tab1_detailed_img_desc'])) ? $_REQUEST['crosssell_tab1_detailed_img_desc'] : ''), [
    'cols' => 50,
    'rows' => 4
]);

if($event->getResults()) {
    $handlerResult = $event->getResults()[0]->getType();
    /*if (isset($arResult['EXTRA_SETTINGS'])){
        $extraSettings = unserialize($arResult['EXTRA_SETTINGS']);
    }*/
    $itemTemplate = array();
    if (is_array($handlerResult['template_field'])){
        foreach ($handlerResult['template_field'] as $templateName){
            $itemTemplate['REFERENCE'][$templateName] = $templateName;
            $itemTemplate['REFERENCE_ID'][$templateName] = $templateName;
        }
        $tabControl->BeginCustomField( "ITEM_TEMPLATE", Loc::getMessage('CROSSELL_PAGE_TAB_1_TEMPLATE'), false );
        ?>
        <tr id="ITEM_TEMPLATE">
            <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
            <td width="60%">
                <?echo SelectBoxFromArray('ITEM_TEMPLATE', $itemTemplate, (isset($extraSettings['ITEM_TEMPLATE'])) ? $extraSettings['ITEM_TEMPLATE']:'' ,'',"class='template-select'",'','style="min-width:350px"');?>
            </td>
        </tr>
        <?
        $tabControl->EndCustomField( "ITEM_TEMPLATE" );
    }
    $itemSlider = array();
    if (is_array($handlerResult['show_slider_field'])){
        foreach ($handlerResult['show_slider_field'] as $showSlider){
            $itemSlider['REFERENCE'][$showSlider] = $showSlider;
            $itemSlider['REFERENCE_ID'][$showSlider] = $showSlider;
        }
        $tabControl->BeginCustomField( "USE_SLIDER", Loc::getMessage('CROSSELL_PAGE_TAB_1_SLIDER'), false );
        ?>
        <tr id="USE_SLIDER">
            <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
            <td width="60%">
                <?echo SelectBoxFromArray('USE_SLIDER', $itemSlider, (isset($extraSettings['USE_SLIDER'])) ? $extraSettings['USE_SLIDER']:'','',"class='slider-select'",'','style="min-width:350px"');?>
            </td>
        </tr>
        <?
        $tabControl->EndCustomField( "USE_SLIDER" );
    }
}

// crosssell-tab2 //-----------------------------------------------------------------------------------------------------------------------------------------------
$tabControl->BeginNextFormTab();
$tabControl->BeginCustomField( "condition_tab2", Loc::getMessage('CROSSELL_PAGE_TAB_2__EDIT_CONDITION') . ":", false );
    ?>
    <tr id="tr_CONDITIONS_tab2">
        <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="60%">
            <div id="tree_tab_2" style="position: relative; z-index: 1;"></div><?
            if (! is_array( $condition['RULE1'] ))
            {
                if (CheckSerializedData( $condition['RULE1'] ))
                {
                    $condition['RULE1'] = unserialize( $condition['RULE1'] );
                }
                else
                {
                    $condition['RULE1'] = '';
                }
            }
            $obCond = new \SotbitCrosssellCatalogCondTree();
            $boolCond = $obCond->Init( BT_COND_MODE_DEFAULT, BT_COND_BUILD_CATALOG, array (
                'FORM_NAME' => 'tabControl_form',
                'CONT_ID' => 'tree_tab_2',
                'JS_NAME' => 'JSCond',
                'INPUT' => 'rule1'
            ) );
            if (! $boolCond)
            {

                if ($ex = $APPLICATION->GetException())
                    echo $ex->GetString() . "<br>";
            }
            else
            {
                $obCond->Show(!$condiFromForm ? $condition['RULE1'] : $TMP_SAVE_COND['rule1']);
            }
            ?></td>
    </tr>
    <?
$tabControl->EndCustomField( "condition_tab2" );

// crosssell-tab3 //-----------------------------------------------------------------------------------------------------------------------------------------------
if($TYPE == 'CROSSSELL') {
    $tabControl->BeginNextFormTab();
    $tabControl->BeginCustomField( "condition_tab3", Loc::getMessage('CROSSELL_PAGE_TAB_3__EDIT_CONDITION') . ":", false );
    ?>
    <tr id="tr_CONDITIONS_tab3">
        <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="60%">
            <div id="tree_tab_3" style="position: relative; z-index: 1;"></div><?
            if (! is_array( $condition['RULE2'] ))
            {
                if (CheckSerializedData( $condition['RULE2'] ))
                {
                    $condition['RULE2'] = unserialize( $condition['RULE2'] );
                }
                else
                {
                    $condition['RULE2'] = '';
                }
            }
            $obCond = new \SotbitCrosssellCatalogCondTree();
            $boolCond = $obCond->Init( BT_COND_MODE_DEFAULT, BT_COND_BUILD_CATALOG, array (
                'FORM_NAME' => 'tabControl_form',
                'CONT_ID' => 'tree_tab_3',
                'JS_NAME' => 'JSCond',
                'INPUT' => 'rule2'
            ) );
            if (! $boolCond)
            {
                if ($ex = $APPLICATION->GetException())
                    echo $ex->GetString() . "<br>";
            }
            else
            {
                $obCond->Show( !$condiFromForm ? $condition['RULE2'] : $TMP_SAVE_COND['rule2'] );
            }
            ?></td>
    </tr>
    <?
    $tabControl->EndCustomField( "condition_tab3" );

    $tabControl->BeginCustomField( "tab_3_property_link", Loc::getMessage('CROSSELL_PAGE_TAB_3_SORT_ORDER'), false );
    if (! is_array( $condition['RULE3'] ))
    {
        if (CheckSerializedData( $condition['RULE3'] ))
        {
            $condition['RULE3'] = unserialize( $condition['RULE3'] );
        }
        else
        {
            $condition['RULE3'] = '';
        }
    }

    ?>
    <tr id="tab_3_property_link">
        <td width="40%"><? echo Loc::getMessage('CROSSELL_PAGE_TAB_3_PROPERTY_LINK_LABEL'); ?></td>
        <td width="60%">
            <?echo CustomSelectBoxMFromArray('tab_3_property_link[]', $propertyFields, (isset($condition['RULE3'])) ? $condition['RULE3']:'' ,'',false,'7','style="min-width:350px"');?>
        </td>
    </tr>
    <?
    $tabControl->EndCustomField( "tab_3_property_link" );

}

    $tabControl->AddFieldGroup("tab_3_sec_sort", Loc::getMessage('CROSSELL_PAGE_TAB_3_SORT_SEC'), ['tab_3_sort_by', 'tab_3_sort_order']);

    $tabControl->BeginCustomField( "tab_3_sort_order", Loc::getMessage('CROSSELL_PAGE_TAB_3_SORT_ORDER'), false );
    ?>
    <tr id="tab_3_sort_order">
        <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="60%">
            <?echo SelectBoxFromArray('tab_3_sort_order', $sortOrderOptions, (isset($arResult['SORT_ORDER'])) ? $arResult['SORT_ORDER']:'' ,'',false,'','style="min-width:350px"');?>
        </td>
    </tr>
    <?
    $tabControl->EndCustomField( "tab_3_sort_order" );


    $tabControl->BeginCustomField( "tab_3_sort_by", Loc::getMessage('CROSSELL_PAGE_TAB_3_SORT_BY'), false );
    ?>
    <tr id="tab_3_sort_by">
        <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="60%">
            <?echo SelectBoxFromArray('tab_3_sort_by', $sortByOptions, (isset($arResult['SORT_BY'])) ? $arResult['SORT_BY']:'' ,'',false,'','style="min-width:350px"');?>
        </td>
    </tr>
    <?
    ?>
    <script>
        $(document).ready(function() {
            var selected_option = $('#tab_3_sort_by option:selected');
            var sort_order = $('select#tab_3_sort_order option:selected');
            if (selected_option.val() == 'other' && (sort_order.val() != 'rand')){
                $('#tr_tab_3_sort_by_other').fadeIn();
            } else {
                $('#tr_tab_3_sort_by_other').fadeOut();
            }
            if (sort_order.val() == 'rand'){
                $('select#tab_3_sort_by').attr("disabled", true);
            } else {
                $('select#tab_3_sort_by').attr("disabled", false);
            }
        });

        $("#tab_3_sort_by").live("change", function() {
            setTimeout(()=>{
                if ($(this).val() == ''){
                    return;
                }
                if (($('#tab_3_sort_by option:selected').val() == 'other') && ($('select#tab_3_sort_order option:selected').val() != 'rand')){
                    $('#tr_tab_3_sort_by_other').fadeIn();
                } else {
                    $('#tr_tab_3_sort_by_other').fadeOut();
                }
            }, 100);
        });

        $("#tab_3_sort_order").live("change", function() {
            setTimeout(()=>{
                if ($(this).val() == ''){
                    return;
                }
                if ($(this).val() == 'rand'){
                    $('select#tab_3_sort_by').attr("disabled", true);
                    if($('#tab_3_sort_by option:selected').val() == 'other'){
                        $('#tr_tab_3_sort_by_other').fadeOut();
                    }
                } else {
                    $('select#tab_3_sort_by').attr("disabled", false);
                    if($('#tab_3_sort_by option:selected').val() == 'other'){
                        $('#tr_tab_3_sort_by_other').fadeIn();
                    }
                }
            }, 100);

        });
    </script>
    <?
    $tabControl->EndCustomField( "tab_3_sort_by" );

    $tabControl->AddEditField("tab_3_sort_by_other", Loc::GetMessage( "CROSSELL_PAGE_TAB_3_SORT_BY_OTHER_TEXT" ), false, array(
        "size" => 37.8,
        "maxlength" => 255,
    ), ($is_sort_by_other) ? $custum_other_sort_by:false);


    $tabControl->AddEditField("tab_3_number_products", Loc::GetMessage( "CROSSELL_PAGE_TAB_3_NUMBER_PRODUCTS" ), false, array(
        "size" => 15,
        "maxlength" => 255
    ), (isset($arResult['NUMBER_PRODUCTS'])) ? $arResult['NUMBER_PRODUCTS']:10);

// Custum js handler //----------------------------------------------------------------------------------------------------------------------------------------------
    $_REQUEST['ID'] = htmlspecialcharsbx($_REQUEST['ID']);
if(isset($_REQUEST['ID'])) {
    ?>
        <script>
            $(document).ready(function() {
                $("#btn_generate").remove();
                $('.adm-detail-toolbar-right').css("display", "flex");
                <?if($_REQUEST["ID"]):?>
                $('.adm-detail-toolbar-right:first').prepend(`
                    <div class="generate_group" style="position: relative;">
                        <input id="generate" type="submit" value="<?= Loc::GetMessage( "SOTBIT_CROSSSELL_GENERATE" )?>">
                    </div>
                `);
                <?endif;?>
                $("#generate").click(function() {
                    $("div").remove(".success-msg");
                    $('.generate_group:first').prepend(`
                           <div class="adm-btn-load-img" style="
                                    z-index: 10;
                                    left: 45%;
                                    top: 6px;">
                    `);
                    $("#generate").attr("disabled", true);
                    $.ajax({
                        url: "/bitrix/admin/ajax_handler.php",
                        type: "post",
                        data: { id : <?= (!empty(htmlspecialcharsbx($_REQUEST['ID'])) ? htmlspecialcharsbx($_REQUEST['ID']) : "0") ?>} ,
                        success: function (response) {
                            $(".adm-detail-toolbar-right:first").prepend(`
                                <div class="success-msg">
                                  <i class="fa fa-check"></i>
                                  <?= Loc::GetMessage( "SOTBIT_CROSSSELL_GENERATE_SUC" )?> ${response}
                                </div>
                             `);
                            $("div").remove(".adm-btn-load-img");
                            $("#generate").attr("disabled", false);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                });
            });
        </script>
    <?
} else {
    ?>
        <script>
            $(document).ready(function() {
                $("#btn_generate").remove();
            });
        </script>
    <?
}

?>
<script>
    $("#tab_1_type").live("change", function() {
        var slted = $('#tab_1_type option:selected');
        // console.log(location.href);
        if (slted.val() == 'COLLECTION'){
            location.href = location.href.replace('CROSSSELL' ,'COLLECTION');
        } else {
            location.href = location.href.replace('COLLECTION' ,'CROSSSELL');
        }
    });
</script>
<?

// FORM Action //-----------------------------------------------------------------------------------------------------------------------------------------------
if($APPLICATION->GetGroupRight('sotbit.crosssell') == "W")
    $tabControl->Buttons($arParams);


$tabControl->arParams["FORM_ACTION"] = $APPLICATION->GetCurPageParam();
$tabControl->Show();
$tabControl->ShowWarnings($tabControl->GetName(), $message);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>
