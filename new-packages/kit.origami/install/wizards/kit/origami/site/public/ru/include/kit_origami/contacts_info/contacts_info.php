<?use Kit\Origami\Helper\Config;
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss(SITE_DIR . "include/kit_origami/contacts_info/style.css");

?>
<div class="contact__techno_block__detail">
	<div class="contact__techno_block__detail_item">
		<div class="contact__techno_block__detail_item__img">
            <svg class="contact__techno_block-icon" width="28" height="28">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_contacts_pin"></use>
            </svg>
		</div>
		<div class="contact__techno_block__detail_item__content">
			<div class="contact__techno_block__detail_item__content_name">
				 Адрес:
			</div>
			<div class="contact__techno_block__detail_item__content_text">
                <?if ($useRegion && $_SESSION['KIT_REGIONS']['UF_ADDRESS']) {
                    echo $_SESSION["KIT_REGIONS"]["NAME"].', '.$_SESSION["KIT_REGIONS"]["UF_ADDRESS"];
                }
                else{
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/contacts_city.php"));
                }
                ?>
				 <br>
			</div>
		</div>
	</div>
	<div class="contact__techno_block__detail_item">
		<div class="contact__techno_block__detail_item__img">
            <svg class="contact__techno_block-icon" width="28" height="28">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_contacts_phone"></use>
            </svg>
            <!-- <i class="icon-phone-call"></i> -->
		</div>
		<div class="contact__techno_block__detail_item__content">
			<div class="contact__techno_block__detail_item__content_name">
				 Телефон:
			</div>
			<div class="contact__techno_block__detail_item__content_text">
                <?
                if ($useRegion && $_SESSION["KIT_REGIONS"]["UF_PHONE"]) {
                	if(is_array($_SESSION["KIT_REGIONS"]["UF_PHONE"])) {
                        foreach (
                            $_SESSION["KIT_REGIONS"]["UF_PHONE"] as $numtel
                        ) {
                            echo $numtel.'<br>';
                        }
                    }
                	else{
                        echo $_SESSION["KIT_REGIONS"]["UF_PHONE"];
	                }
                }
                else{
                    echo '8 029 840 25 20';
                }
                ?>
			</div>
		</div>
	</div>
	<div class="contact__techno_block__detail_item">
		<div class="contact__techno_block__detail_item__img">
            <svg class="contact__techno_block-icon" width="28" height="28">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_contacts_mail"></use>
            </svg>
            <!-- <i class="icon-new-email-outline"></i> -->
		</div>
		<div class="contact__techno_block__detail_item__content">
			<div class="contact__techno_block__detail_item__content_name">
				 E-mail:
			</div>
			<div class="contact__techno_block__detail_item__content_text">

     <?if ($useRegion && $_SESSION["KIT_REGIONS"]["UF_EMAIL"]) {
     	if(is_array($_SESSION["KIT_REGIONS"]["UF_EMAIL"])){
     		foreach($_SESSION["KIT_REGIONS"]["UF_EMAIL"] as $email){
     			if($email) {
                    ?>
			        <a href="mailto:<?= $email ?>">
                        <?= $email ?>
			        </a>
                    <?
                }
	        }
        }
     	else{
     		?>
	        <a href="mailto:<?=$_SESSION["KIT_REGIONS"]["UF_EMAIL"]?>">
                <?echo $_SESSION["KIT_REGIONS"]["UF_EMAIL"];?>
	        </a>
	        <?
        }
     	?>
     <?}
     else{
         $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/kit_origami/contacts_email.php"));
     }
     ?>
			</div>
		</div>
	</div>
	<div class="contact__techno_block__detail_item">
		<div class="contact__techno_block__detail_item__img">
            <svg class="contact__techno_block-icon" width="28" height="28">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_contacts_clock"></use>
            </svg>
            <!-- <i class="icon-clock"></i> -->
		</div>
		<div class="contact__techno_block__detail_item__content">
			<div class="contact__techno_block__detail_item__content_name">
				 Режим работы:
			</div>
			<div class="contact__techno_block__detail_item__content_text">
				 Пн-Пт: с 8.00 до 20.00
			</div>
		</div>
	</div>
</div>
