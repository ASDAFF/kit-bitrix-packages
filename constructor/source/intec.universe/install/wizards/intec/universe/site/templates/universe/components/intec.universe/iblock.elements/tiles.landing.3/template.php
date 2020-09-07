<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$frame = $this->createFrame()->begin();

if (!empty($arResult)) { ?>
    <div class="owl-carousel carusel-products owl-theme">
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
            <div class="view-item clearfix">
                <div class="item-wrapper clearfix" style="padding:18px 13px">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
                       class="left_block"
                       style="background-image:url('<?=$arItem['PREVIEW_PICTURE']["SRC"]?>')">
                    </a>
                    <div class="right_block">
                        <a class="name intec-cl-text-hover"
                           href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                            <?= TruncateText($arItem["NAME"],45) ?>
                        </a>
                        <div class="price">
                            <?= number_format($arItem["PROPERTIES"][$arParams["NAME_PROP_PRICE"]]["VALUE"], 0, '', ' ') ?> <?= GetMessage("RUB") ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <script>
        $(document).ready(function() {
            var owl = $('.carusel-products');
            owl.owlCarousel({
                margin: 10,
                navRewind: false,
                nav:false,
                dots:true,
                responsive: {
                    0: { items:1 },
                    500: { items:2 },
                    900: { items:2 },
                    1000: { items:4 }
                }
            })
        })
    </script>
<?php }

$frame->end() ?>