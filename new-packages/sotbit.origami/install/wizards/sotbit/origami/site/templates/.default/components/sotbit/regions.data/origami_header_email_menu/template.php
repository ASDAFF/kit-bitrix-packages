<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->createFrame()->begin();
if(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE']) && count($arResult['FIELDS']['UF_EMAIL']['VALUE']) > 1):
    $email = reset($arResult['FIELDS']['UF_EMAIL']['VALUE']);
	?>
	<div class="container_menu_mobile__phone_block">
		<a href="mailto:<?=$email?>" class="container_menu__contact_item__email origami_icons_button">
            <?=$email?>
		</a>
		<div class="many_tels_wrapper">
			<div class="many_tels">
				<?foreach($arResult['FIELDS']['UF_EMAIL']['VALUE'] as $i => $email):?>
					<?if($i == 0)
						continue;?>
                    <a href="mailto:<?=$email?>" class="container_menu__contact_item__email">
	                    <?=$email?>
                    </a>
                <?endforeach;?>
			</div>
		</div>
	</div>
<?elseif(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE'])):
    $email = reset($arResult['FIELDS']['UF_EMAIL']['VALUE']);
    ?>
	<div class='container_menu__contact_item_wrapper'>
		<a href="mailto:<?=$email?>" class="container_menu__contact_item__email">
            <?=$email?>
		</a>
	</div>
<?else:
    $email = $arResult['FIELDS']['UF_EMAIL']['VALUE'];
    ?>
	<div class='container_menu__contact_item_wrapper'>
		<a href="mailto:<?=$email?>" class="container_menu__contact_item__email">
			<?=$email?>
		</a>
	</div>
<?endif;?>