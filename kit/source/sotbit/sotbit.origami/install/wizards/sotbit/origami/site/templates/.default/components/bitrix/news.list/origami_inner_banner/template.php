<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));

Asset::getInstance()->addCss(SITE_DIR . "local/templates/.default/components/bitrix/news.list/origami_inner_banner/style.css");

if($arResult['ITEMS'])
{
	foreach ($arResult['ITEMS'] as $item)
	{
		?>
		<div class="detail_blog__banner">
			<?
            $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<a href="<? echo(SITE_DIR);?><?=$item['PROPERTIES']['URL']['VALUE']?>" title="<?=$item["NAME"]?>" target="<?=($item['PROPERTIES']['NEW_TAB']['VALUE'] == 'Y')?'_blank':''?>" class="<?=$hoverClass?>">
				<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" width="<?=$item['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$item['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$item['PREVIEW_PICTURE']['ALT']?>" title="<?=$item['PREVIEW_PICTURE']['TITLE']?>">
				<div class="detail_blog__banner_bg"></div>
				<?
				if($item['PROPERTIES']['BUTTON_TEXT']['VALUE'])
				{
					?>
					<div class="banner_button">
						<div class="main_btn sweep-to-right btn-sm">
							<?=$item['PROPERTIES']['BUTTON_TEXT']['VALUE']?>
						</div>
					</div>
					<?
				}
				?>
			</a>
		</div>
		<?
	}
}
?>
