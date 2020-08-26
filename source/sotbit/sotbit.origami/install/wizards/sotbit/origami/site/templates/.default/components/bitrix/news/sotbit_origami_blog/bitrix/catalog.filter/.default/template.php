<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<form style="display:none" name="<?echo $arResult["FILTER_NAME"]."_form"?>"
      action="<?echo
$arResult["FORM_ACTION"]?>" method="get" id="tagsForm">
	<?foreach($arResult["ITEMS"] as $arItem):
		if(array_key_exists("HIDDEN", $arItem)):
			echo $arItem["INPUT"];
		endif;
	endforeach;?>
	<table class="data-table" cellspacing="0" cellpadding="2">
	<thead>
		<tr>
			<td colspan="2" align="center"><?=GetMessage("IBLOCK_FILTER_TITLE")?></td>
		</tr>
	</thead>
	<tbody>
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if(!array_key_exists("HIDDEN", $arItem)):?>
				<tr>
					<td valign="top"><?=$arItem["NAME"]?>:</td>
					<td valign="top"><?=$arItem["INPUT"]?></td>
				</tr>
			<?endif?>
		<?endforeach;?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2">
				<input type="submit" name="set_filter" value="<?=GetMessage("IBLOCK_SET_FILTER")?>" /><input type="hidden" name="set_filter" value="Y" />&nbsp;&nbsp;<input type="submit" name="del_filter" value="<?=GetMessage("IBLOCK_DEL_FILTER")?>" /></td>
		</tr>
	</tfoot>
	</table>
</form>
<?
if($arResult['TAGS'])
{
	?>
	<div class="tags_wrapper">
		<div class="tags">
			<div class="tags__title">
				<?=GetMessage("IBLOCK_FILTER_TITLE")?>
			</div>
			<?
			foreach($arResult['TAGS'] as $tag)
			{
				?>
				<div class="tag <?=($tag == $arResult['ITEMS']['TAGS']['INPUT_VALUE'])
				?'active':''?>"
				     onclick="clickTag('<?=$tag?>',
						'<?=$arParams['FILTER_NAME']?>')">
					<?=$tag?>
				</div>
				<?
			}
			?>
		</div>
	</div>
	<?
}
?>