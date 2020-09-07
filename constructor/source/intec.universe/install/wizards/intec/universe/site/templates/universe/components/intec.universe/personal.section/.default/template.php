<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <div class="row">
            <div class="col-md-12 sale-personal-section-index">
                <div class="row sale-personal-section-row-flex">
                    <?php if (!empty($arResult['URL']['ORDER'])) { ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="sale-personal-section-index-block intec-cl-background">
                                <a class="sale-personal-section-index-block-link" href="<?= $arResult['URL']['ORDER'] ?>">
                                    <span class="sale-personal-section-index-block-ico">
                                        <i class="fa fa-calculator"></i>
                                    </span>
                                    <h2 class="sale-personal-section-index-block-name">
                                        <?= Loc::getMessage("PS_ORDERS") ?>
                                    </h2>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arResult['URL']['PERSONAL_DATA'])) { ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="sale-personal-section-index-block intec-cl-background">
                                <a class="sale-personal-section-index-block-link" href="<?= $arResult['URL']['PERSONAL_DATA'] ?>">
                                    <span class="sale-personal-section-index-block-ico">
                                        <i class="fa fa-user-secret"></i>
                                    </span>
                                    <h2 class="sale-personal-section-index-block-name">
                                        <?= Loc::getMessage("PS_PERSONAL_DATA") ?>
                                    </h2>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arResult['URL']['BASKET'])) { ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="sale-personal-section-index-block intec-cl-background">
                                <a class="sale-personal-section-index-block-link" href="<?= $arResult['URL']['BASKET'] ?>">
                                    <span class="sale-personal-section-index-block-ico">
                                        <i class="fa fa-shopping-cart"></i>
                                    </span>
                                    <h2 class="sale-personal-section-index-block-name">
                                        <?= Loc::getMessage("PS_BASKET") ?>
                                    </h2>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arResult['URL']['CONTACTS'])) { ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="sale-personal-section-index-block intec-cl-background">
                                <a class="sale-personal-section-index-block-link" href="<?= $arResult['URL']['CONTACTS'] ?>">
                                    <span class="sale-personal-section-index-block-ico">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                    <h2 class="sale-personal-section-index-block-name">
                                        <?= Loc::getMessage("PS_CONTACTS") ?>
                                    </h2>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>