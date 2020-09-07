<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use intec\core\helpers\Html;

/** @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */
$this->setFrameMode(true);?>
<?php if (!empty($arResult)){ ?>
	<?php $i = 0; ?>
	<div class="footer-menu">
		<?php  foreach($arResult as $arItem) { ?>
			<?php
            if($i > 2) {
				break;
			}
			$i++;

            $bActive = $arItem['ACTIVE'];
            $sUrl = $bActive ? null : $arItem['LINK'];
            $sTag = $bActive ? 'span' : 'a';
			?>
			<div class="root-item">
                <?= Html::Tag($sTag, $arItem["TEXT"], [
                    'class' => Html::cssClassFromArray([
                        'root-link' => true,
                        'intec-cl-text' => true,
                        'active' => $arItem["SELECTED"] ? true : false
                    ], true),
                    'href' => $sUrl
                ]) ?>
				<?php if($arItem["ITEMS"]) { ?>
					<ul class="child-menu intec-ui-mod-simple">
						<?php foreach($arItem["ITEMS"] as $child) { ?>
                        <?php
                            $sChildUrl = $child['ACTIVE'] ? null : $child['LINK'];
                            $sChildTag = $child['ACTIVE'] ? 'span' : 'a';
                        ?>
							<li class="child-item">
                                <?= Html::Tag($sChildTag, $child["TEXT"], [
                                    'class' => Html::cssClassFromArray([
                                        'child-link' => true,
                                        'intec-cl-text-hover' => true,
                                        'intec-cl-text' => $child["SELECTED"],
                                        'active' => $child["SELECTED"]
                                    ], true),
                                    'href' => $sChildUrl
                                ]) ?>
							</li>
						<?php }?>
					</ul>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
<?php } ?>