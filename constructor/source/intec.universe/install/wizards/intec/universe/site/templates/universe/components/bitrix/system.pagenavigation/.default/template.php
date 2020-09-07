<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (!$arResult['NavShowAlways'])
	if ($arResult['NavRecordCount'] == 0 || ($arResult['NavPageCount'] == 1 && $arResult['NavShowAll'] == false))
		return;

$sNavQueryString = ($arResult['NavQueryString'] != '' ? $arResult['NavQueryString'].'&amp;' : '');
$sNavQueryStringFull = ($arResult['NavQueryString'] != '' ? '?'.$arResult['NavQueryString'] : '');

?>
<div class="ns-bitrix c-system-pagenavigation c-system-pagenavigation-default">
	<div class="system-pagenavigation-items">
		<div class="system-pagenavigation-items-wrapper">
            <?php if ($arResult['bDescPageNumbering'] === true) { ?>
                <?php if ($arResult['NavPageNomer'] < $arResult['NavPageCount']) { ?>
                    <?php if ($arResult['bSavePage']) { ?>
                        <div class="system-pagenavigation-item system-pagenavigation-item-previous">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['NavPageNomer'] + 1 ?>">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </div>
                        <div class="system-pagenavigation-item">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['NavPageNomer'] + 1 ?>">1</a>
                        </div>
                    <?php } else { ?>
                        <?php if (($arResult['NavPageNomer'] + 1) == $arResult['NavPageCount']) { ?>
                            <div class="system-pagenavigation-item system-pagenavigation-item-previous">
                                <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?><?= $sNavQueryStringFull ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="system-pagenavigation-item system-pagenavigation-item-previous">
                                <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult["NavPageNomer"] + 1 ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="system-pagenavigation-item">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?><?= $sNavQueryStringFull ?>">1</a>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="system-pagenavigation-item system-pagenavigation-item-previous system-pagenavigation-item-disabled">
                        <div class="system-pagenavigation-item-wrapper">
                            <i class="fas fa-angle-left"></i>
                        </div>
                    </div>
                    <div class="system-pagenavigation-item system-pagenavigation-item-active">
                        <div class="system-pagenavigation-item-wrapper intec-cl-background">1</div>
                    </div>
                <?php } ?>
                <?php $arResult['nStartPage']-- ?>
                <?php while ($arResult['nStartPage'] >= $arResult['nEndPage'] + 1) { ?>
                    <?php $NavRecordGroupPrint = $arResult['NavPageCount'] - $arResult['nStartPage'] + 1 ?>
                    <?php if ($arResult['nStartPage'] == $arResult['NavPageNomer']) { ?>
                        <div class="system-pagenavigation-item system-pagenavigation-item-active">
                            <div class="system-pagenavigation-item-wrapper intec-cl-background"><?= $NavRecordGroupPrint ?></div>
                        </div>
                    <?php } else { ?>
                        <div class="system-pagenavigation-item">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['nStartPage'] ?>"><?= $NavRecordGroupPrint ?></a>
                        </div>
                    <?php } ?>
                    <?php $arResult['nStartPage']-- ?>
                <?php } ?>
                <?php if ($arResult['NavPageNomer'] > 1) { ?>
                    <?php if ($arResult['NavPageCount'] > 1) { ?>
                        <div class="system-pagenavigation-item">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=1"><?= $arResult['NavPageCount'] ?></a>
                        </div>
                    <?php } ?>
                    <div class="system-pagenavigation-item system-pagenavigation-item-next">
                        <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['NavPageNomer'] - 1 ?>">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    </div>
                <?php } else { ?>
                    <?php if($arResult['NavPageCount'] > 1) { ?>
                        <div class="system-pagenavigation-item system-pagenavigation-item-active">
                            <div class="system-pagenavigation-item-wrapper intec-cl-background"><?= $arResult['NavPageCount'] ?></div>
                        </div>
                    <?php } ?>
                    <div class="system-pagenavigation-item system-pagenavigation-item-next system-pagenavigation-item-disabled">
                        <div class="system-pagenavigation-item-wrapper">
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <?php if ($arResult['NavPageNomer'] > 1) { ?>
                    <?php if ($arResult['bSavePage']) { ?>
                        <div class="system-pagenavigation-item system-pagenavigation-item-previous">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['NavPageNomer'] - 1 ?>">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </div>
                        <div class="system-pagenavigation-item">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=1">1</a>
                        </div>
                    <?php } else { ?>
                        <?php if ($arResult['NavPageNomer'] > 2) { ?>
                            <div class="system-pagenavigation-item system-pagenavigation-item-previous">
                                <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['NavPageNomer'] - 1 ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="system-pagenavigation-item system-pagenavigation-item-previous">
                                <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?><?= $sNavQueryStringFull ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="system-pagenavigation-item">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?><?= $sNavQueryStringFull ?>">1</a>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="system-pagenavigation-item system-pagenavigation-item-previous system-pagenavigation-item-disabled">
                        <div class="system-pagenavigation-item-wrapper">
                            <i class="fas fa-angle-left"></i>
                        </div>
                    </div>
                    <div class="system-pagenavigation-item system-pagenavigation-item-active">
                        <div class="system-pagenavigation-item-wrapper intec-cl-background">1</div>
                    </div>
                <?php } ?>
                <?php $arResult["nStartPage"]++ ?>
                <?php while ($arResult['nStartPage'] <= $arResult['nEndPage'] - 1) { ?>
                    <?php if ($arResult['nStartPage'] == $arResult['NavPageNomer']) { ?>
                        <div class="system-pagenavigation-item system-pagenavigation-item-active">
                            <div class="system-pagenavigation-item-wrapper intec-cl-background"><?= $arResult['nStartPage'] ?></div>
                        </div>
                    <?php } else { ?>
                        <div class="system-pagenavigation-item">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['nStartPage'] ?>"><?= $arResult['nStartPage'] ?></a>
                        </div>
                    <?php } ?>
                    <?php $arResult['nStartPage']++ ?>
                <?php } ?>
                <?php if ($arResult['NavPageNomer'] < $arResult['NavPageCount']) { ?>
                    <?php if ($arResult['NavPageCount'] > 1) { ?>
                        <div class="system-pagenavigation-item">
                            <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['NavPageCount'] ?>"><?= $arResult['NavPageCount'] ?></a>
                        </div>
                    <?php } ?>
                    <div class="system-pagenavigation-item system-pagenavigation-item-next">
                        <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>PAGEN_<?= $arResult['NavNum'] ?>=<?= $arResult['NavPageNomer'] + 1 ?>">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    </div>
                <?php } else { ?>
                    <?php if ($arResult['NavPageCount'] > 1) { ?>
                        <div class="system-pagenavigation-item system-pagenavigation-item-active">
                            <div class="system-pagenavigation-item-wrapper intec-cl-background"><?= $arResult['NavPageCount'] ?></div>
                        </div>
                    <? } ?>
                        <div class="system-pagenavigation-item system-pagenavigation-item-next system-pagenavigation-item-disabled">
                            <div class="system-pagenavigation-item-wrapper">
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                <?php } ?>
            <?php } ?>
            <?php if ($arResult['bShowAll']) { ?>
                <?php if ($arResult['NavShowAll']) { ?>
                    <div class="system-pagenavigation-item system-pagenavigation-item-all">
                        <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>SHOWALL_<?= $arResult['NavNum'] ?>=0" rel="nofollow"><?= Loc::getMessage('C_SYSTEM_PAGENAVIGATION_DEFAULT_PAGES') ?></a>
                    </div>
                <?php } else { ?>
                    <div class="system-pagenavigation-item system-pagenavigation-item-all">
                        <a class="system-pagenavigation-item-wrapper" href="<?= $arResult['sUrlPath'] ?>?<?= $sNavQueryString ?>SHOWALL_<?= $arResult['NavNum'] ?>=1" rel="nofollow"><?= Loc::getMessage('C_SYSTEM_PAGENAVIGATION_DEFAULT_ALL') ?></a>
                    </div>
                <?php } ?>
            <?php } ?>
		</div>
	</div>
</div>