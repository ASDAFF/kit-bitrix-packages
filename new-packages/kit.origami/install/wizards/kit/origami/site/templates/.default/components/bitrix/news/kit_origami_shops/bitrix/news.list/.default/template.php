<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);

if($arResult['SECTIONS'] && $arResult['ITEMS'])
{
	?>
	<div class="shops">
		<?
		foreach($arResult['SECTIONS'] as $section)
		{
	        ?>
			<div class="row">
				<div class="col-12">
					<div class="shops__section_name">
	                    <?=$section['NAME']?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="show__header">
						<div class="col-5">
							<div class="shops__address shops__column_title">
			                    <?=GetMessage('SHOP_ADDRESS')?>
							</div>
						</div>
						<div class="col-2">
							<div class="shops__metro shops__column_title">
			                    <?=GetMessage('SHOP_METRO')?>
							</div>
						</div>
						<div class="col-2">
							<div class="shops__phone shops__column_title">
			                    <?=GetMessage('SHOP_PHONE')?>
							</div>
						</div>
						<div class="col-3">
							<div class="shops__phone shops__column_title">
			                    <?=GetMessage('SHOP_PAYMENT')?>
							</div>
						</div>
					</div>
				</div>
			</div>
	        <?
			if($arResult['ITEMS'])
			{
				foreach($arResult['ITEMS'] as $item)
				{
					if($item['IBLOCK_SECTION_ID'] != $section['ID'])
					{
						continue;
					}
                    ?>
					<div class="row show__body">
						<div class="col-12 col-xs-12 col-sm-12 col-lg-5">
							<div class="shops__content">
                                <?
                                if($item['PREVIEW_PICTURE']['SRC'])
                                {
                                    ?>
									<div class="shops__img">
										<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>">
									</div>
                                <?}?>
								<div class="shops__info">
									<div class="shops__info_name">
                                        <svg class="contacts_icon_location" width="12" height="12">
                                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_location_filled"></use>
                                        </svg>
										<!-- <i class="fas fa-map-marker-alt"></i> -->
                                        <?=$item['PROPERTIES']['ADDRESS']['VALUE']?>
									</div>
									<div class="shops__info_shedule">
                                        <?=$item['PROPERTIES']['SCHEDULE']['VALUE']['TEXT']?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-lg-2">
							<div class="shops__info_metro">
                                <?
                                if($item['PROPERTIES']['METRO']['VALUE'])
                                {
                                	foreach($item['PROPERTIES']['METRO']['VALUE'] as $metro)
	                                {
	                                	?>
										<div class="shops__info_metro_row">
                                            <svg class="contacts_icon_metro" width="14" height="14">
                                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_metro"></use>
                                            </svg>
											<!-- <i class="icon-moscow-metro-logo"></i> -->
                                            <?=$metro?>
										</div>
		                                <?
	                                }
                                }
                                ?>
							</div>
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-lg-2">
							<div class="shops__info_phone">
                                <?
                                if($item['PROPERTIES']['PHONE']['VALUE'])
                                {
                                    foreach($item['PROPERTIES']['PHONE']['VALUE'] as $phone)
                                    {
                                        ?>
										<div class="shops__info_phone_row">
                                            <svg class="contacts_phone-icon" width="12" height="12">
                                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
                                            </svg>
											<!-- <i class="fas fa-phone"></i> -->
                                            <?=$phone?>
										</div>
                                        <?
                                    }
                                }
                                ?>
							</div>
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-lg-3">
							<div class="shops__info_payment">
                                <?
                                if($item['PROPERTIES']['PAY_TYPE']['VALUE'])
                                {
                                    foreach($item['PROPERTIES']['PAY_TYPE']['VALUE'] as $i => $payment)
                                    {
                                    	?>
	                                    <div class="shops__info_payment_row">
                                        <?
                                    	switch ($item['PROPERTIES']['PAY_TYPE']['VALUE_XML_ID'][$i])
	                                    {
		                                    case 'mastercard':
                                                echo '<i class="icon-mastercard"></i>';
		                                    	break;
		                                    case 'visa':
		                                    	echo '<i class="icon-visa-pay-logo"></i>';
		                                    	break;
		                                    case 'money':
                                                echo '<i class="icon-dollar-money-cash"></i>';
		                                    	break;
	                                    }
                                        ?>
                                            <?=$payment?>
										</div>
                                        <?
                                    }
                                }
                                ?>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="show__body_under"></div>
						</div>
					</div>
                    <?
				}
			}
		}
		?>
	</div>
	<?
}

?>