<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin();
?>
<div class="mobile-menu-contact mobile-menu-contact--address">
    <div class="mobile-menu-contact__block">
        <svg class="mobile-menu-contact-icon" width="14" height="14">
            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_send_filled"></use>
        </svg>
        <?
        if(is_array($arResult['FIELDS']['UF_ADDRESS']['VALUE']) && count($arResult['FIELDS']['UF_ADDRESS']['VALUE']) > 1):
        $address = reset($arResult['FIELDS']['UF_ADDRESS']['VALUE']);
        ?>
        <?=$address?>
        <?elseif(is_array($arResult['FIELDS']['UF_ADDRESS']['VALUE'])):
        $address = reset($arResult['FIELDS']['UF_ADDRESS']['VALUE']);
        ?>
        <?=$address?>
        <?else:
        $address = $arResult['FIELDS']['UF_ADDRESS']['VALUE'];
        ?>
        <?=$address?>
        <?endif;?>
    </div>
</div>

<div class="mobile-menu-contact mobile-menu-contact--email">
    <?if(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE']) && count($arResult['FIELDS']['UF_EMAIL']['VALUE']) > 1):
    $email = reset($arResult['FIELDS']['UF_EMAIL']['VALUE']);
    ?>
    <div class="mobile-menu-contact__block mobile-menu-contact__block--popup">
        <a href="mailto:<?=$email?>" class="mobile-menu-contact__block-popup">
            <svg class="mobile-menu-contact-icon mobile-menu-contact-icon--email" width="14" height="14">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mail_filled"></use>
            </svg>
            <?=$email?>
            <span class="mobile-menu-contact__block-btn">
                <svg class="mobile-menu-contact-icon mobile-menu-contact-icon--arrow" width="10" height="10">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                </svg>
            </span>
        </a>
        <div class="mobile-menu-contact__block-more">
            <?foreach($arResult['FIELDS']['UF_EMAIL']['VALUE'] as $i => $email):?>
                <?if($i == 0)
                    continue;?>
                <a href="mailto:<?=$email?>" class="mobile-menu-contact__item">
                    <?=$email?>
                </a>
            <?endforeach;?>
        </div>
    </div>
    <?elseif(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE'])):
    $email = reset($arResult['FIELDS']['UF_EMAIL']['VALUE']);
    ?>
    <div class='mobile-menu-contact__block'>
        <a href="mailto:<?=$email?>" class="mobile-menu-contact__item">
            <svg class="mobile-menu-contact-icon mobile-menu-contact-icon--email" width="14" height="14">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mail_filled"></use>
            </svg>
            <?=$email?>
        </a>
    </div>
    <?else:
    $email = $arResult['FIELDS']['UF_EMAIL']['VALUE'];
    ?>
    <div class='mobile-menu-contact__block'>
        <a href="mailto:<?=$email?>" class="mobile-menu-contact__item">
            <svg class="mobile-menu-contact-icon mobile-menu-contact-icon--email" width="14" height="14">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mail_filled"></use>
            </svg>
            <?=$email?>
        </a>
    </div>
    <?endif;?>
</div>


<div class="mobile-menu-contact mobile-menu-contact--phone">
    <?if(is_array($arResult['FIELDS']['UF_PHONE']['VALUE']) && count($arResult['FIELDS']['UF_PHONE']['VALUE']) > 1):
    $phone = reset($arResult['FIELDS']['UF_PHONE']['VALUE']);
    ?>
    <div class="mobile-menu-contact__block mobile-menu-contact__block--popup">
        <a href="tel:<?=$phone?>" class="mobile-menu-contact__block-popup">
                <svg class="mobile-menu-contact-icon mobile-menu-contact-icon--phone" width="14" height="14">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
                </svg>
            <?=$phone?>
            <span class="mobile-menu-contact__block-btn">
                <svg class="mobile-menu-contact-icon mobile-menu-contact-icon--arrow" width="10" height="10">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                </svg>
            </span>
        </a>
        <div class="mobile-menu-contact__block-more">
            <?foreach($arResult['FIELDS']['UF_PHONE']['VALUE'] as $i => $phone):?>
                <?if($i == 0)
                    continue;?>
                <a href="tel:<?=$phone?>" class="mobile-menu-contact__item">
                    <?=$phone?>
                </a>
            <?endforeach;?>
        </div>
    </div>
    <?elseif(is_array($arResult['FIELDS']['UF_PHONE']['VALUE'])):
    $phone = reset($arResult['FIELDS']['UF_PHONE']['VALUE']);
    ?>
    <div class='mobile-menu-contact__block'>
        <a href="tel:<?=$phone?>" class="mobile-menu-contact__item">
                <svg class="mobile-menu-contact-icon mobile-menu-contact-icon--phone" width="14" height="14">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
                </svg>
            <?=$phone?>
        </a>
    </div>
    <?else:
    $phone = $arResult['FIELDS']['UF_PHONE']['VALUE'];
    ?>
    <div class='mobile-menu-contact__block'>
        <a href="tel:<?=$phone?>" class="mobile-menu-contact__item">
            <svg class="mobile-menu-contact-icon mobile-menu-contact-icon--phone" width="14" height="14">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
            </svg>
            <?=$phone?>
        </a>
    </div>
    <?endif;?>
</div>
