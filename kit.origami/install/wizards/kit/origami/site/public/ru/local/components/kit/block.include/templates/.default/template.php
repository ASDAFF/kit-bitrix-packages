<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Kit\Origami\Helper\Config;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

Loc::loadMessages(__FILE__);
if($arResult['CAN_CHANGE'])
{
    Asset::getInstance()->addJs($templateFolder . '/plugins/ui/jquery-ui.min.js');
    Asset::getInstance()->addCss($templateFolder . '/plugins/ui/jquery-ui.min.css');
}

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$page = $request->getScriptFile();
$page = str_replace('index.php','',$page);
$builderSite = Config::get('SITE_BUILDER');

?>

<div class="blocks-wrapper">

<?
if($arResult['CAN_CHANGE'])
{
	if($arResult['CAN_SAVE'] && $builderSite == 'Y')
	{
        ?>
<!--		<div class="landing-ui-panel landing-ui-panel-top">-->
<!--			<div class="ui-btn landing-ui-button ui-btn-primary" data-id="save">-->
<!--                --><?//= Loc::getMessage(KitOrigami::moduleId.'_SAVE') ?>
<!--            </div>-->
<!--		</div>-->
        <?
    }?>
	<div data-id="blocks_panel" class="blocks_panel landing-ui-panel landing-ui-panel-content landing-ui-panel-block-list
	   landing-ui-hide" data-is-shown="true" data-part="<?=$arParams['PART']?>" hidden>
		<div class="landing-ui-panel-content-element landing-ui-panel-content-header">
			<div class="landing-ui-panel-content-title">
                <?=Loc::getMessage(KitOrigami::moduleId.'_TITLE')?>
			</div>
		</div>
		<div class="landing-ui-panel-content-element landing-ui-panel-content-body no-footer">
			<div class="landing-ui-panel-content-body-sidebar">
                <?if($arResult['AVAILABLE_BLOCKS'])
                {
                    $i = 0;
                    foreach($arResult['AVAILABLE_BLOCKS'] as $section => $block)
                    {
                        ?>
						<button type="button" class="landing-ui-button1
						landing-ui-button-sidebar
						landing-ui-button-sidebar-child
						<?=($i == 0)?'landing-ui-active':''?>" data-id="<?=$section?>">
							<span class="landing-ui-button-text">
                                <?=$arResult['AVAILABLE_SECTIONS'][$section]?>
                            </span>
						</button>
                        <?
                        ++$i;
                    }
                }
                ?>
			</div>
			<div class="landing-ui-panel-content-body-content">
                <?
                if($arResult['AVAILABLE_BLOCKS'])
                {
                    $i = 0;
                    foreach($arResult['AVAILABLE_BLOCKS'] as $section => $blocks)
                    {
                        foreach($blocks as $block)
                        {
                            ?>
							<div class="landing-ui-card1 landing-ui-card-block-preview
							<?=($i == 0)?'landing-ui-card-block-active':'landing-ui-card-block-noactive'?>" data-code="<?=$block['CODE']?>" data-section="<?=$section?>">
                                <h6 class="landing-ui-card-block-preview__title"><?=$block['SETTINGS']['block']['name']?></h6>
								<div class="landing-ui-card-body">
									<div class="landing-ui-card-block-preview-image-container">
										<img style="opacity: 1" src="<?=SITE_TEMPLATE_PATH?>/assets/img/loader_lazy.svg" data-src="<?=$block['PREVIEW']?>" alt="<?=$block['SETTINGS']['block']['name']?>" title="<?=$block['SETTINGS']['block']['name']?>" class="lazy-ui">
                                        <span class="loader-lazy"></span>
                                    </div>
								</div>
							</div>
                            <?
                        }
                        ++$i;
                    }
                }
                ?>
			</div>
		</div>
		<div class="landing-ui-panel-content-element landing-ui-panel-content-footer"></div>
		<button type="button" class="landing-ui-button landing-ui-panel-content-close" data-id="close"
		        title="<?=Loc::getMessage(KitOrigami::moduleId.'_CLOSE')?>">
			<span class="landing-ui-button-text"></span>
		</button>
	</div>

    <?
}
?>

<div id="blocks_<?=$arParams['PART']?>"<?if(!isset($_COOKIE["origamiConstructMode"]) || $_COOKIE["origamiConstructMode"] != "Y"):?> class="site-builder__hide"<?else:?>class="site-builder__show"<?endif;?>>
    <?$arResult['BLOCK_COLLECTION']->show($arResult['CAN_CHANGE']);?>
</div>

</div>

<script>
	BX.message(
		{
			'show':'<?=Loc::getMessage(KitOrigami::moduleId.'_SHOW')?>',
			'hide':'<?=Loc::getMessage(KitOrigami::moduleId.'_HIDE')?>',
			'cut':'<?=Loc::getMessage(KitOrigami::moduleId.'_CUT')?>',
			'copy':'<?=Loc::getMessage(KitOrigami::moduleId.'_COPY')?>',
			'paste':'<?=Loc::getMessage(KitOrigami::moduleId.'_PASTE')?>',
		}
	);
	var Block = new Blocks({
		'tab':'.landing-ui-button-sidebar-child',
		'classAdd':'.landing-ui-button-plus',
		'block':'.landing-ui-card1',
		'site':'<?=SITE_ID?>',
		'siteTemplate':'<?=SITE_TEMPLATE_ID?>',
		'close':'.landing-ui-panel-content-close',
		'part':'<?=$arParams['PART']?>',
		'page':'<?=$page?>',
        'btnSwitchSave':'#switch-constructor__save',
        'btnSwitchOn':'#switch-constructor',
        'btnSwitchOff':'#switch-constructor__off',
	});

</script>
