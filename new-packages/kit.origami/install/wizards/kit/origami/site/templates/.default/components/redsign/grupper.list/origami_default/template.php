<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Localization\Loc;
?>
<div class="rs-grupper">
<?foreach($arResult["GROUPED_ITEMS"] as $arrValue):?>
	<?if(is_array($arrValue["PROPERTIES"]) && count($arrValue["PROPERTIES"])>0):?>
		<p class="rs-grupper__section-title"><?=$arrValue["GROUP"]["NAME"]?></p>
		<ul class="rs-grupper__section">
		<?foreach($arrValue["PROPERTIES"] as $property):?>
			<li class="rs-grupper__section-item">
				<span class="rs-grupper__item-name"><?=$property["NAME"]?>
					<?if($property["HINT"]):?>
					<span class="rs-grupper__item-hint-button">?
						<span class="rs-grupper__item-hint"><?=$property["HINT"]?></span>
					</span>
					<?endif;?>
				</span>

				<span class="rs-grupper__item-value"><?= (is_array($property['DISPLAY_VALUE']) ? implode(' / ', $property['DISPLAY_VALUE']) : $property['DISPLAY_VALUE']) ?></span>
			</li>
		<?endforeach;?>
		</ul>
	<?endif;?>
<?endforeach;?>
		<p class="rs-grupper__section-title"><?=Loc::getMessage("OTHER_ITEMS")?></p> 
<?if(is_array($arResult["NOT_GROUPED_ITEMS"]) && count($arResult["NOT_GROUPED_ITEMS"])>0):?>
	<ul class="rs-grupper__section">
		<?foreach($arResult["NOT_GROUPED_ITEMS"] as $property):?>
			<li class="rs-grupper__section-item">
				<span class="rs-grupper__item-name"><?=$property["NAME"]?>
					<?if($property["HINT"]):?>
					<span class="rs-grupper__item-hint-button">?
						<span class="rs-grupper__item-hint"><?=$property["HINT"]?></span>
					</span>
					<?endif;?>
				</span>
				<span class="rs-grupper__item-value"><?=(is_array($property['DISPLAY_VALUE']) ? implode(' / ', $property['DISPLAY_VALUE']) : $property['DISPLAY_VALUE']) ?></span>
			</li>
		<?endforeach;?>
	</ul>
<?endif;?>
</div>