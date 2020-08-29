<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->createFrame()->begin();
if(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE']) && count($arResult['FIELDS']['UF_EMAIL']['VALUE']) > 1):
    $email = reset($arResult['FIELDS']['UF_EMAIL']['VALUE']);
    ?>
	<div class="footer-link__email fonts__middle_comment dropdown_list">
		<div class='main_element_wrapper'>
			<a href="mailto:<?=$email?>" class="footer-link__email-main">
                <?=$email?>
			</a>
		</div>
		<div class="many_wrapper">
			<div class="wrapper_items">
                <?
                foreach($arResult['FIELDS']['UF_EMAIL']['VALUE'] as $i => $email){
                    if($i == 0){
                        continue;
                    }
                    ?>
					<a href="mailto:<?=$email?>" class="footer-link__email-item">
                        <?=$email?>
					</a>
                    <?
                }
                ?>
			</div>
		</div>
	</div>

<?elseif(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE'])):
    $email = reset($arResult['FIELDS']['UF_EMAIL']['VALUE']);
    ?>
	<div class='footer-link__email fonts__middle_comment'>
		<div class='main_element_wrapper'>
			<a href="mailto:<?=$email?>">
                <?=$email?>
			</a>
		</div>
	</div>
<?else:
    $email = $arResult['FIELDS']['UF_EMAIL']['VALUE'];
    ?>
	<div class='footer-link__email fonts__middle_comment'>
		<div class='main_element_wrapper'>
			<a href="mailto:<?=$email?>">
                <?=$email?>
			</a>
		</div>
	</div>
<?endif;?>