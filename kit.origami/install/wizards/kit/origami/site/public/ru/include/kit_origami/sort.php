<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Kit\Origami\Config\Option;
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;

Loc::loadMessages(__FILE__);

function stripslashes_array($arr) {
    if (!is_array($arr)) return stripslashes($arr);
    $out = array();
    foreach ($arr as $key=>$value) {
        $out[stripslashes($key)]=stripslashes_array($value);
    }
    return $out;
}

$sort = [
    'by' => [
        'by' => $arParams['ELEMENT_SORT_FIELD'],
        'order' => $arParams['ELEMENT_SORT_ORDER']
    ],
    'limit' => ['limit' => $arParams['PAGE_ELEMENT_COUNT']]
];

$arTemplateListView = array("card", "list");
$viewDefault = "card";

if(!isset($arParams["TEMPLATE_LIST_VIEW"]))
{
    $arTemplateListView = Config::getArray("TEMPLATE_LIST_VIEW");
}

if(!isset($arParams["TEMPLATE_LIST_VIEW_DEFAULT"]))
{
    $viewDefault = Config::get("TEMPLATE_LIST_VIEW_DEFAULT");
}

if($_GET['sort_by'])
{
    $_SESSION['sort']['by']['by'] = $_GET['sort_by'];
    $_SESSION['sort']['by']['order'] = $_GET['sort_order'];
    $_SESSION['sort']['limit'] = $_GET['cnt'];

}

if($_GET['view'])
{
    $_SESSION['view'] = $_GET['view'];
}

if($_SESSION['view'])
{
    $viewDefault = $_SESSION['view'];
}

if($_SESSION['sort'])
{
    $sort = [
        'by' => [
            'by' => $_SESSION['sort']['by']['by'],
            'order' => $_SESSION['sort']['by']['order']
        ],
        'limit' => ['limit' => $_SESSION['sort']['limit']]
    ];

    $defSortByVal = $_SESSION['sort']['by']['by'];
    $defSortOrder = $_SESSION['sort']['by']['order'];
    $defLim = $_SESSION['sort']['limit'];
}

if(empty($defSortByVal))
    $defSortBy = Option::get('DEFAULT_SORT_TAB_');

$sortBy = Option::get('TAB_SORT_');
if(!empty($sortBy))
    $sortBy = unserialize($sortBy);

$activeSort = 0;

foreach ($sortBy as $key => $item)
{
    if(empty($item))
        continue;

    $nameSortBy[] = Option::get('NAME_TAB_'.$item.'_');
    $codeSortBy[] = Option::get('CODE_TAB_' . $item . '_');
    $sortOrder[] = Option::get('SORT_ORDER_TAB_' . $item . '_');


    if(!isset($defSortByVal) && isset($defSortBy) && $item == $defSortBy)
    {
        $defSortByVal = Option::get('CODE_TAB_' . $item . '_');
        $activeSort = $key;
    }elseif(isset($defSortByVal) && !isset($defSortBy) && $defSortByVal == Option::get('CODE_TAB_' . $item . '_') && $defSortOrder == Option::get('SORT_ORDER_TAB_' . $item . '_'))
    {
        $activeSort = $key;
        $defSortBy = $item;
    }
}


for($i = 0; $i < 5; $i++){
    $sortLimit[$i] = Option::get('COUNT_COUNT_TAB_' . $i . '_');
}
if(empty($defLim))
    $defLim = $sortLimit[Option::get('DEFAULT_COUNT_TAB_')];


if(!is_array($nameSortBy) || count(array_diff($nameSortBy, array(''))) == 0) {
    $sortBy = ['SORT_FIELD_1', 'SORT_FIELD_2'];
    $nameSortBy = [Loc::getMessage('SORT_NAME'), Loc::getMessage('SORT_CREATED')];
    $codeSortBy = ['NAME', 'CREATED_DATE'];
    $sortOrder = ['ASC', 'DESC'];
    $defSortBy = ['SORT_FIELD_1'];
}

if(!is_array($sortLimit) || count(array_diff($sortLimit, array(''))) == 0){
    $sortLimit = [14, 28];
    $defLim = 14;
}


if($_SESSION['sort']['limit']) {
    $limSession = $_SESSION['sort']['limit'];
    $defLim = stripslashes_array($limSession);
}


if($_SESSION['sort']['by']['by'])
    $defSortByVal = $_SESSION['sort']['by']['by'];

if(!isset($sort['by']['by']) || empty($sort['by']['by']) && !empty($defSortByVal))
    $sort['by']['by'] = $defSortByVal;
if(!isset($sort['limit']['limit']) || empty($sort['limit']['limit']) && !empty($defLim))
    $sort['limit']['limit'] = $defLim;
if(!isset($sort['by']['order']) || empty($sort['by']['order']))
    $sort['by']['order'] = $sortOrder[( array_search($defSortBy, $sortBy) !== false ? array_search($defSortBy, $sortBy) : 'asc' )];

?>

	<div class="catalog_content__sort_horizon">
		<form name="sort" method="get" id="sort-section">
			<span class="catalog_content__sort_horizon_title fonts__main_comment"><?= Loc::getMessage('SORT') ?></span>
			<div class="catalog_content__sort_horizon_property">
				<div class="select_block <?if ($sort['by']['order'] == 'asc'):?>sort-asc<?else:?>sort-desc<?endif;?>">
					<select name="sort_by" onchange="this.form.submit()" class="custom-select-block sources
                       	<?preg_match('/\d+/', $defSortBy, $defNumber);?>
						fonts__middle_comment" data-placeholder="<?=($activeSort + 1);?>">
                        <? foreach ($nameSortBy as $key => $value)
                        {
                            if(!empty($value))
                            {
                            ?>
							<option value="<?= $codeSortBy[$key] ?>" <?= ($key == $activeSort)
                                ? 'selected' : '' ?>><?=$value?></option>
                            <?
                            }
                        } ?>

					</select>
                    <select class="sort-orders" name="sort_order" style="display: none;">
                        <? foreach ($sortOrder as $orderK => $order) {
                            ?>
                            <option class="sort-order" value="<?=$order;?>" <?=( $orderK == $defNumber[0] ? "selected" : "" )?>><?=$order;?></option>
                            <?
                        }?>
                    </select>
				</div>
			</div>
			<span class="catalog_content__sort_horizon_title fonts__main_comment"><?= Loc::getMessage('SORT_NUMBER') ?></span>
			<div class="catalog_content__sort_horizon_property">
				<div class="select_block">
					<select name="cnt" onchange="this.form.submit()" class="custom-select-block sources
						fonts__middle_comment" data-placeholder="<?=$defLim?>">
                        <? for ($i = 0; $i < 5; $i++ )
                        {
                            if(!empty($sortLimit[$i])){
                                ?>
                                <option value="<?= $sortLimit[$i] ?>" <?= ($sortLimit[$i] == $defLim)
                                    ? 'selected' : '' ?>><?=$sortLimit[$i]?></option>
                                <?
                            }
                        } ?>
					</select>
				</div>
			</div>
            <?=( !empty($_REQUEST['q']) ? "<input type='hidden' name='q' value='".$_REQUEST['q']."'>" : '' )?>
            <?=( !empty($_REQUEST['q']) ? "<input type='hidden' name='s' value='".$_REQUEST['s']."'>" : '' )?>

            <?
            if(!empty($arTemplateListView))
            {
                ?>
                <div class="catalog_content__sort_horizon_btn-block">
                    <?
                    $active = false;
                    foreach($arTemplateListView as $view)
                    {
                        if($viewDefault == $view)
                        {
                            $active = true;
                        }

                        if($active)
                        {
                            ?>
                            <span class="catalog_content__sort_horizon_btn" title="<?=Config::getTemplateListView()[$view]?>">
                            <?
                        }else{
                            ?>
                            <a class="catalog_content__sort_horizon_btn" onclick="" href="<?=$APPLICATION->GetCurPageParam("view=".$view, array("view", "ajaxFilter"), false);?>" rel="nofollow" title="<?=Config::getTemplateListView()[$view]?>">
                            <?
                        }
                        if($view == "card")
                        {
                            ?>
                            <svg class="catalog_content__sort_horizon_btn-titles" width="20" height="20">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_tiles"></use>
                            </svg>
                            <?
                        }elseif($view == "list")
                        {
                            ?>
                            <svg class="catalog_content__sort_horizon_btn-list" width="30" height="20">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_list"></use>
                            </svg>
                            <?
                        }
                        if($active)
                        {
                            ?>
                            </span>
                            <?
                        }else{
                            ?>
                            </a>
                            <?
                        }


                        $active = false;
                        ?>

                        <?
                    }
                    ?>
                </div>
                <?
            }





            /*?>
            <div class="catalog_content__sort_horizon_btn-block">
                <span class="catalog_content__sort_horizon_btn">
                    <svg class="catalog_content__sort_horizon_btn-titles" width="20" height="20">
                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_tiles"></use>
                    </svg>
                </span>
                <a class="catalog_content__sort_horizon_btn" href="#" rel="nofollow">
                    <svg class="catalog_content__sort_horizon_btn-list" width="30" height="20">
                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_list"></use>
                    </svg>
                </a>
            </div>
            <?*/?>
		</form>
	</div>
<?if(\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()) :?>
    <script>
        MainSelect();
    </script>
<?endif;?>
<?
$sort["view"] = $viewDefault;

return $sort;
?>


