<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->createFrame()->begin();
if(is_array($arResult['FIELDS']['UF_PHONE']['VALUE']) && count($arResult['FIELDS']['UF_PHONE']['VALUE']) > 1):
    $phone = reset($arResult['FIELDS']['UF_PHONE']['VALUE']);
    ?>
	<div class="footer-link__phone fonts__middle_comment dropdown_list">
		<div class='main_element_wrapper'>
			<a href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>" class="footer-link__phone-main">
                <?=$phone?>
			</a>
		</div>
		<div class="many_wrapper">
			<div class="wrapper_items">
                <?
                foreach($arResult['FIELDS']['UF_PHONE']['VALUE'] as $i => $phone){
                    if($i == 0){
                        continue;
                    }
                    ?>
					<a href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>" class="footer-link__phone-item">
                        <?=$phone?>
					</a>
                    <?
                }
                ?>
			</div>
		</div>
	</div>

<?elseif(is_array($arResult['FIELDS']['UF_PHONE']['VALUE'])):
    $phone = reset($arResult['FIELDS']['UF_PHONE']['VALUE']);
    ?>
	<div class='footer-link__phone fonts__middle_comment'>
		<div class='main_element_wrapper'>
			<a href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>">
                <?=$phone?>
			</a>
		</div>
	</div>
<?else:
    $phone = $arResult['FIELDS']['UF_PHONE']['VALUE'];
    ?>
	<div class='footer-link__phone fonts__middle_comment'>
		<div class='main_element_wrapper'>
			<a href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>">
                <?=$phone?>
			</a>
		</div>
	</div>
<?endif;?>