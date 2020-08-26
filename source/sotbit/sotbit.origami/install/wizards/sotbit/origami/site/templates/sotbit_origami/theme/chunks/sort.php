<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Sotbit\Origami\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$sort = [
    'by' => [
        'by' => $arParams['ELEMENT_SORT_FIELD'],
        'order' => $arParams['ELEMENT_SORT_ORDER']
    ],
    'limit' => ['limit' => $arParams['PAGE_ELEMENT_COUNT']]
];

if($_GET['sort_by'])
{
    $_SESSION['sort']['by']['by'] = $_GET['sort_by'];
    $_SESSION['sort']['by']['order'] = $_GET['sort_order'];
    $_SESSION['sort']['limit'] = $_GET['cnt'];
    if($_GET['q'])
        $_SESSION['q'] = $_GET['q'];
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

    $defSortBy = $_SESSION['sort']['by']['by'];
    $defLim = $_SESSION['sort']['limit'];
}

$sortBy = Option::get('TAB_SORT_');
if(!empty($sortBy))
    $sortBy = unserialize($sortBy);

foreach ($sortBy as $key => $item){
    if(empty($item))
        continue;
    $nameSortBy[] = Option::get('NAME_TAB_'.$item.'_');
    $codeSortBy[] = Option::get('CODE_TAB_' . $item . '_');
    $sortOrder[] = Option::get('SORT_ORDER_TAB_' . $item . '_');
    if(!empty($defSortBy) && stristr($codeSortBy[$key], $defSortBy) !== false && $sortOrder[$key] == $sort['by']['order'])
        $defSortBy = $item;
}
if(empty($defSortBy))
    $defSortBy = Option::get('DEFAULT_SORT_TAB_');

for($i = 0; $i < 5; $i++){
    $sortLimit[$i] = Option::get('COUNT_COUNT_TAB_' . $i . '_');
}
if(empty($defLim))
    $defLim = $sortLimit[Option::get('DEFAULT_COUNT_TAB_')];

?>

	<div class="catalog_content__sort_horizon">
		<form name="sort" method="get" id="sort-section">
			<span class="catalog_content__sort_horizon_title fonts__main_comment"><?= Loc::getMessage('SORT') ?></span>
			<div class="catalog_content__sort_horizon_property">
				<div class="select_block <?if ($sort['by']['order'] == 'asc'):?>sort-asc<?else:?>sort-desc<?endif;?>">
					<select name="sort_by" onchange="this.form.submit()" class="custom-select-block sources
					<?preg_match('/\d+/', $defSortBy, $defNumber)?>
						fonts__middle_comment" data-placeholder="<?=$nameSortBy[$defNumber[0]];?>">
                        <? foreach ($nameSortBy as $key => $value)
                        {
                            if(!empty($value)){
                            ?>
							<option value="<?= $codeSortBy[$key] ?>" <?= ($defSortBy == $sortBy[$key])
                                ? 'selected' : '' ?>><?= Loc::getMessage('SORT_PO')." ". $value ?></option>
                            <?
                            }
                        } ?>
					</select>
                    <select class="sort-orders" name="sort_order" style="display: none;">
                        <? foreach ($sortOrder as $orderK => $order) {
                            ?>
                            <option class="sort-order" value="<?=$order;?>" <?=( $orderK == $defNumber[0] ? "selected" : "" )?>></option>
                            <?
                        }?>
                    </select>
				</div>
			</div>
			<div class="catalog_content__sort_horizon_property">
				<div class="select_block">
					<select name="cnt" onchange="this.form.submit()" class="custom-select-block sources
						fonts__middle_comment" data-placeholder="<?=Loc::getMessage('SORT_PO')." ". $defLim;?>">
                        <? for ($i = 0; $i < 5; $i++ )
                        {
                            if(!empty($sortLimit[$i])){
                                ?>
                                <option value="<?= $sortLimit[$i] ?>" <?= ($sortLimit[$i] == $defLim)
                                    ? 'selected' : '' ?>><?=Loc::getMessage('SORT_PO')." ". $sortLimit[$i] ?></option>
                                <?
                            }
                        } ?>
					</select>
				</div>
			</div>
		</form>
	</div>
<?if(\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()) :?>
    <script>
        MainSelect();
    </script>
<?endif;?>
<?
//$arParams['sort'] = $sort;
return $sort;
?>


